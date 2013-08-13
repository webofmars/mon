#
# app
#
set :application,    "mon"
set :domain,         "#{application}.jrns.fr"
set :app_path,       "app"

#
# Repository
#
set :repository,     "ssh://git@bitbucket.org/webofmars/mon.git"
set :scm,            :git

#
# Server connection
#
set :serverName,     "#{application}.jrns.fr"
set :user,           "frederic.leger"

#
# Deploy settings
#

# Remote location where the project will be stored
set :deploy_to,      "/var/www/#{application}"

# Deploy strategy
# set :deploy_via,     :copy

# Roles
role :web,           domain                   # Your HTTP server, Apache/etc
role :app,           domain                   # This may be the same as your `Web` server
role :db,            domain, :primary => true # This is where Symfony2 migrations will run

set :use_sudo,       false
set :use_composer,   true
set :composer_options,  "--no-dev --verbose --prefer-dist --optimize-autoloader"

# Update vendors during the deploy
# after 1st deploy you might want to change this to false. If true it'll install vendors each time
set :update_vendors, false

# Set some paths to be shared between versions
set :shared_files,    [ app_path + "/config/parameters.yml", web_path + "/.htaccess"]
set :shared_children, [ app_path + "/logs", app_path + "/cache", web_path + "/uploads", "vendor"]

set :writable_dirs,       ["app/cache", "app/logs"]
set :webserver_user,      "apache"
set :permission_method,   :chown
set :use_set_permissions, true

# To prevent "you must have a tty to run sudo" message
default_run_options[:pty] = true
ssh_options[:keys] = [ "~/.ssh/id_rsa" ]
ssh_options[:forward_agent] = true
set  :git_enable_submodules, 0
set :dump_assetic_assets, true

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

# The number of releases which will remain on the server
set :keep_releases,  3

# Clean old releases after deploy
# after "deploy:update", "deploy:cleanup"