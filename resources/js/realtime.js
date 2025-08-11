
class RealtimeService {
    constructor(config = {}) {
        this.config = {
            enabled: false,
            driver: 'pusher',
            pusher: {},
            channels: {},
            fallbackPollingInterval: 30000,
            ...config
        };
        
        this.connection = null;
        this.channels = new Map();
        this.subscribers = new Map();
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.pollingIntervals = new Map();
        
        this.init();
    }

    init() {
        if (!this.config.enabled) {
            this.initPollingFallback();
            return;
        }

        try {
            this.initWebSocketConnection();
        } catch (error) {
            this.initPollingFallback();
        }
    }

    initWebSocketConnection() {
        if (this.config.driver === 'pusher' && window.Pusher) {
            this.connection = new Pusher(this.config.pusher.key, {
                cluster: this.config.pusher.cluster,
                encrypted: this.config.pusher.encrypted,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Accept': 'application/json',
                    }
                }
            });

            this.connection.connection.bind('connected', () => {
                this.isConnected = true;
                this.reconnectAttempts = 0;
                this.emit('connected');
            });

            this.connection.connection.bind('disconnected', () => {
                this.isConnected = false;
                this.emit('disconnected');
                this.handleReconnection();
            });

            this.connection.connection.bind('error', (error) => {
                this.emit('error', error);
                this.handleReconnection();
            });
        }
    }

    initPollingFallback() {
        this.startPolling('notifications', '/api/notifications/latest', this.config.fallbackPollingInterval);
        this.startPolling('orders', '/api/orders/latest', this.config.fallbackPollingInterval * 2);
    }

    startPolling(type, endpoint, interval) {
        if (this.pollingIntervals.has(type)) {
            clearInterval(this.pollingIntervals.get(type));
        }

        const pollData = async () => {
            try {
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.handlePollingData(type, data);
                }
            } catch (error) {
            }
        };

        pollData();
        
        const intervalId = setInterval(pollData, interval);
        this.pollingIntervals.set(type, intervalId);
    }

    handlePollingData(type, data) {
        if (type === 'notifications' && data.notifications) {
            data.notifications.forEach(notification => {
                this.emit('notification.new', notification);
            });
        }
        
        if (type === 'orders' && data.orders) {
            data.orders.forEach(order => {
                this.emit('order.status.updated', {
                    order_id: order.id,
                    new_status: order.estado,
                    product_name: order.product?.nombre,
                    employee_name: order.employee?.nombre,
                    points_used: order.puntos_utilizados,
                    updated_at: order.updated_at
                });
            });
        }
    }

    subscribe(channelName, eventName, callback) {
        const subscriptionKey = `${channelName}:${eventName}`;
        
        if (this.isConnected && this.connection) {
            let channel = this.channels.get(channelName);
            
            if (!channel) {
                channel = this.connection.subscribe(channelName);
                this.channels.set(channelName, channel);
            }
            
            channel.bind(eventName, callback);
        } else {
            if (!this.subscribers.has(subscriptionKey)) {
                this.subscribers.set(subscriptionKey, []);
            }
            this.subscribers.get(subscriptionKey).push(callback);
        }
        
        return () => this.unsubscribe(channelName, eventName, callback);
    }

    unsubscribe(channelName, eventName, callback) {
        const subscriptionKey = `${channelName}:${eventName}`;
        
        if (this.isConnected && this.connection) {
            const channel = this.channels.get(channelName);
            if (channel) {
                channel.unbind(eventName, callback);
            }
        } else {
            const subscribers = this.subscribers.get(subscriptionKey);
            if (subscribers) {
                const index = subscribers.indexOf(callback);
                if (index > -1) {
                    subscribers.splice(index, 1);
                }
            }
        }
    }

    emit(eventName, data = null) {
        const callbacks = this.subscribers.get(eventName) || [];
        callbacks.forEach(callback => {
            try {
                callback(data);
            } catch (error) {
            }
        });
    }

    on(eventName, callback) {
        if (!this.subscribers.has(eventName)) {
            this.subscribers.set(eventName, []);
        }
        this.subscribers.get(eventName).push(callback);
        
        return () => {
            const callbacks = this.subscribers.get(eventName);
            if (callbacks) {
                const index = callbacks.indexOf(callback);
                if (index > -1) {
                    callbacks.splice(index, 1);
                }
            }
        };
    }

    handleReconnection() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            const delay = Math.pow(2, this.reconnectAttempts) * 1000;
            
            setTimeout(() => {
                this.init();
            }, delay);
        } else {
            this.initPollingFallback();
        }
    }

    disconnect() {
        if (this.connection) {
            this.connection.disconnect();
        }
        
        this.pollingIntervals.forEach((intervalId) => {
            clearInterval(intervalId);
        });
        this.pollingIntervals.clear();
        
        this.channels.clear();
        this.isConnected = false;
    }

    getStatus() {
        return {
            connected: this.isConnected,
            driver: this.config.driver,
            channels: Array.from(this.channels.keys()),
            pollingActive: this.pollingIntervals.size > 0,
            reconnectAttempts: this.reconnectAttempts
        };
    }
}

window.realtimeService = null;

document.addEventListener('alpine:init', () => {
    Alpine.data('realtime', () => ({
        connected: false,
        status: 'disconnected',
        
        init() {
            const config = window.realtimeConfig || {};
            window.realtimeService = new RealtimeService(config);
            
            window.realtimeService.on('connected', () => {
                this.connected = true;
                this.status = 'connected';
            });
            
            window.realtimeService.on('disconnected', () => {
                this.connected = false;
                this.status = 'disconnected';
            });
            
            window.realtimeService.on('error', () => {
                this.connected = false;
                this.status = 'error';
            });
        },
        
        subscribe(channel, event, callback) {
            if (window.realtimeService) {
                return window.realtimeService.subscribe(channel, event, callback);
            }
        },
        
        getStatus() {
            return window.realtimeService ? window.realtimeService.getStatus() : null;
        }
    }));
});

document.addEventListener('livewire:init', () => {
    if (window.realtimeService) {
        window.realtimeService.on('order.status.updated', (data) => {
            Livewire.dispatch('orderUpdated', data);
        });
        
        window.realtimeService.on('points.awarded', (data) => {
            Livewire.dispatch('pointsUpdated', data);
        });
        
        window.realtimeService.on('inventory.alert', (data) => {
            Livewire.dispatch('inventoryAlert', data);
        });
    }
});

export default RealtimeService;