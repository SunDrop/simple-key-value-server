#
## Setup project

1. Add record to hosts

```shell
127.0.0.30 kv.local
```

- Mac users only
Inside the docker-compose file, we are using the internal network with a lo0 interface (127.x.x.x)
It's automatically supported on *nix machine, but for MacOS, you need some additional steps.
- Copy content bellow into /Library/LaunchDaemons/com.docker_1270030_alias.plist
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>Label</key>
    <string>com.docker_1270030_alias</string>
    <key>ProgramArguments</key>
    <array>
        <string>ifconfig</string>
        <string>lo0</string>
        <string>alias</string>
        <string>127.0.0.30</string>
    </array>
    <key>RunAtLoad</key>
    <true/>
</dict>
</plist>
```
- Reload LaunchDaemons by restarting the computer or run follow command
```shell
sudo launchctl load /Library/LaunchDaemons/com.docker_1270030_alias.plist
```
2. Run docker compose for build image locally
```shell
make up
```

3. Reload dependency (if needed)
```shell
make composer_install
```
