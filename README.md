  <img src="https://github.com/Steadfast5/resources/blob/master/Steadfast5.png" alt="Steadfast5 logo" title="Aimeos" align="center" />

# Steadfast5 Minecraft: Bedrock Edition Server Software

Click [here](https://github.com/IceCruelStuff/Steadfast5) to view other repository.

Click [here](https://github.com/Steadfast5/Steadfast5/releases/latest/download/Steadfast5.phar) to download the latest release.

[![HitCount](http://hits.dwyl.com/Steadfast5/https://githubcom/Steadfast5/Steadfast5.svg)](http://hits.dwyl.com/Steadfast5/https://githubcom/Steadfast5/Steadfast5) [![License](https://img.shields.io/badge/license-LGNU%20v3-blue.svg?style=flat-square)](https://github.com/Steadfast5/Steadfast5/blob/master/LICENSE) [![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/Steadfast5/Steadfast5) [![GitHub release](https://img.shields.io/github/release/Steadfast5/Steadfast5.svg)](https://github.com/Steadfast5/Steadfast5/releases/latest)

# Credits
Most of the code was written by originally written by [MFDGaming](https://github.com/MFDGaming), [iAldrich23xX](https://github.com/iAldrich23xX), and [LiTEKPE](https://github.com/LiTEKPE).

## Introduction

Steadfast5 is a project for backporting new Minecraft: Bedrock Edition changes to older PocketMine-MP versions for better stability and performance, while retaining as many features from the new PocketMine-MP versions as possible.

## Known bugs

- Players don't fall out of the world naturally, you'll want to handle PlayerMoveEvent as needed to kill them.

## Installation
### Installing on Windows
To install on Windows, please follow these instructions. 
1) Open PowerShell and type in `git clone --recursive https://github.com/IceCruelStuff/Steadfast5.git` 
2) Run the `start.cmd` file located in your server folder. It will automatically download PHP. This will not work if you don't have Git installed. Click [here](https://git-scm.com/downloads) to go to download page for Git.
3) After running `start.cmd`, you have to complete setup wizard. When you are finished all these steps, the server should start up. If you are running Windows 8 and below, this will not work. Click [here](https://github.com/Steadfast5/Steadfast5/wiki/Windows-8-and-below) for instructions on how to install on Windows 8 and below.

### Installing on Linux/MacOS
To install on Linux/MacOS, please follow these instructions.
1) Open command line and type in `git clone --recursive https://github.com/IceCruelStuff/Steadfast5.git`. Then navigate to `Steadfast5` directory using command line. You can also download the zip file from GitHub and extract it.
2) Run command `./installer.sh`. If successful, this will create a `bin` folder with a special PHP build in it. After, run command `./start.sh` and the server should start up.

### Windows Installer
This is only for Windows 10. If you don't know how to do any of the steps above, then download the [`source_installer.cmd`](https://github.com/IceCruelStuff/Steadfast5/releases/download/v1.1/source_installer.cmd) from the releases and run it. You can view the script on [GitHub Gist](https://gist.github.com/IceCruelStuff/621339e30c8fb2b0d4d806265f0bbed9).
This is only the Steadfast5 source installer. Click [here](https://github.com/IceCruelStuff/Steadfast5/releases/download/v1.1/installer.cmd) to download the [`installer.cmd`](https://gist.github.com/IceCruelStuff/f52d1071c1d93b707ead302f96c9f248).

If you don't have git installed or you are running Windows 8 and below, it will be very hard to use scripts provided in this repository. You can click [here](https://git-scm.com/downloads) to go to download page for Git.
