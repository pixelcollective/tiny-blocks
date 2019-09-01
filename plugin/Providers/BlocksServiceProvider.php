<?php
namespace Plugin\Providers;

use TinyPixel\Base\ServiceProvider as BaseProvider;
use Plugin\Blocks\Blocks\Demo;
use Plugin\Blocks\Assets\Demo as DemoAssets;

class BlocksServiceProvider extends BaseProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('block.demo', function ($app) {
            return new Demo($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('block.demo');

        (new DemoAssets())();

        add_filter('render_block_data', [$this, 'filter'], 10, 2);

        $this->loadViewsFrom(__DIR__ . '/../../resources/blocks/views', 'block');
    }

    /**
     * Filter block data.
     *
     * @param  array $block
     * @param  array $source
     * @return array $block
     */
    public function filter($block, $source)
    {
        $block['attrs']['source'] = $source;

        return $block;
    }
}
