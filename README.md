  <img src="https://github.com/MFDGaming/PocketMine-Steadfast3/blob/master/Steadfast3.png" alt="Steadfast3 logo" title="Aimeos" align="center" />

# Steadfast5 Minecraft PE Server Software

# Links
| Jenkins | TravisCI | Discord |
| :---: | :---: | :---: |
| [![Build Status](https://img.shields.io/badge/Build-Passing-brightgreen?style=plastic)]() | [![Travis branch](https://img.shields.io/badge/Build-Passing-orange?style=plastic)]() | [![Discord](https://img.shields.io/badge/Chat-On%20Discord-738BD7.svg?style=plastic&colorB=7289da)](https://discord.gg/fUhjt5n) |

## Introduction

Steadfast5 is a project for backporting new Minecraft: Bedrock Edition changes to older Pocketmine versions for better stability and performance, while retaining as many features from the new PocketMine-MP versions as possible.

## Todo-List

- [x] Forms
- [x] Rcon
- [ ] Add items and blocks
- [x] addTitle function
- [ ] Api update to the latest
- [ ] And more

## Known bugs

- Players don't fall out of the world naturally, you'll want to handle PlayerMoveEvent as needed to kill them.

## Installation
### Installing on Windows
To install on Windows 8 and above, open Powershell and type in 'git clone --recursive https://github.com/IceCruelStuff/Steadfast5.git'. After, run the 'start.cmd' file located in you server folder. It will automatically download PHP. If you are running on Windows 7, download the zip file from Github and extract it, then run 'start.cmd'. After running 'start.cmd', you have to complete setup wizard. When you are finished all these steps, the server should work.

### Installing on Linux/MacOS
To install on Linux/MacOS, please follow these instructions.
1) Open command line and type in 'git clone --recursive https://github.com/IceCruelStuff/Steadfast5.git'. Then navigate to 'Steadfast5' directory using command line. You can also download the zip file from Github and extract it.
2) Run command `./installer.sh`. If successful, this will create a `bin` folder with a special PHP build in it. After, run command './start.sh' and the server should start up.
