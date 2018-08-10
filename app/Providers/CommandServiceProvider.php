<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\KeyGenerateCommand;

class CommandServiceProvider extends ServiceProvider
{
	
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('key:generate', function()
		{
			return new KeyGenerateCommand;
		});
		
		$this->commands(
			'key:generate'
		);
	}
}