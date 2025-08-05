<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;

class WarmCache extends Command
{
    protected $signature = 'cache:warm';
    
    protected $description = 'Warm up application cache with frequently accessed data';

    public function handle()
    {
        $this->info('Warming up application cache...');
        
        $cacheService = app(CacheService::class);
        
        try {
            $cacheService->warmUpCache();
            
            $this->info('✅ Cache warmed successfully');
            

            $stats = $cacheService->getCacheStats();
            $this->table(['Cache Key', 'Status'], 
                collect($stats)->map(function ($data, $key) {
                    return [$key, $data['exists'] ? '✅ Cached' : '❌ Missing'];
                })->toArray()
            );
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to warm cache: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}