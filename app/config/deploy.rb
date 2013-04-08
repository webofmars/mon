set :application, "mon"
set :domain,      "#{application}.jrns.fr"
set :deploy_to,   "/var/www/#{application}"
set :app_path,    "app"

set :repository,  "ssh://frederic.leger@mon.jrns.fr/opt/git/mon.git"
set :scm,         :git

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set :keep_releases,  3

set :user, "frederic.leger"
set :use_sudo, true
set :use_composer, true

#default_run_options[:pty] = true
#set  :git_enable_submodules, 0

set :shared_files,      ["app/config/parameters.ini"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]

# after first deployment you might want to change this to false. Setting to true will always install vendors each time
set :update_vendors,  true

set :dump_assetic_assets, true

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL
