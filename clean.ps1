Remove-Item -Path ./database/* -Recurse -Force 
New-Item ./database/.gitkeep

Remove-Item -Path ./logs/apache2/*.log -Force
New-Item ./logs/apache2/.gitkeep

Remove-Item -Path ./logs/mariadb/*.log -Force
New-Item ./logs/mariadb/.gitkeep

Remove-Item -Path ./logs/xdebug/*.log -Force
New-Item ./logs/xdebug/.gitkeep



docker compose stop
docker compose rm -f
docker system prune -a --volumes -f