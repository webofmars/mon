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
set :user,           "fabien.somnier"

#
# Deploy settings
#

# Remote location where the project will be stored
set :deploy_to,      "/var/www/#{application}"

# Deploy strategy
set :deploy_via,     :copy #remote_cache #rsync_with_remote_cache

# Exclusion of files
set :copy_exclude, "**/*_dev.*"

# Roles
role :web,           domain                   # Your HTTP server, Apache/etc
role :app,           domain                   # This may be the same as your `Web` server
role :db,            domain, :primary => true # This is where Symfony2 migrations will run

# The number of releases which will remain on the server
set :keep_releases,  3

set :use_sudo,       false
set :use_composer,   true

# Update vendors during the deploy
# after 1st deploy you might want to change this to false. If true it'll install vendors each time
set :update_vendors, true

# Set some paths to be shared between versions
set :shared_files,    ["app/config/parameters.ini"]
set :shared_children, [app_path + "/logs", web_path + "/uploads", "vendor"]

# To prevent "you must have a tty to run sudo" message
default_run_options[:pty] = true

ssh_options[:keys] = [ "~/.ssh/jrns_fabiensomnier_rsa" ]
ssh_options[:forward_agent] = true

#set  :git_enable_submodules, 0

#set :dump_assetic_assets, true

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

# Command to launch at the deploy end
namespace :deploy do
  task :restart, :roles => :web do
    run "touch #{ current_path }/tmp/restart.txt"
  end

  task :restart_daemons, :roles => :app do
    sudo "monit restart all -g daemons"
  end
end
