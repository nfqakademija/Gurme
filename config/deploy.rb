# config valid only for Capistrano 3.1
lock '3.1.0'

set :application, 'Gurme'
set :repo_url, 'https://github.com/nfqakademija/Gurme.git'

# Default branch is :master
# ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }.call

# Default deploy_to directory is /var/www/my_app
set :deploy_to, '/home/gurme/public_html'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
set :linked_files, %w{app/config/parameters.yml}

# Default value for linked_dirs is []
set :linked_dirs, %w{app/logs app/cache vendor web/images/dishes}

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
set :keep_releases, 5


namespace :deploy do

  before :publishing, :restart

  before :restart, :clear_cache do
    on roles(:web) do
        execute "cd #{release_path} && composer install"
        execute "cd #{release_path} && php app/console doctrine:schema:update --force"
        execute "cd #{release_path} && php app/console cache:clear"
        execute "cd #{release_path} && npm install"
        execute "cd #{release_path} && bower install"
        execute "cd #{release_path} && gulp init"
        execute "cd #{release_path} && gulp"
        execute "cd #{release_path}/../../shared/web/images && chmod 777 dishes"
    end
  end
  after :finishing, 'deploy:cleanup'
end
