<img src="https://github.com/Steadfast5/resources/blob/master/Steadfast5.png" alt="Steadfast5 logo" title="Aimeos" align="center" />

# Steadfast5 Minecraft: Bedrock Edition Server Software

| ![Download icon](https://storage.googleapis.com/material-icons/external-assets/v4/icons/svg/ic_file_download_black_18px.svg) Latest Release | License |
| :---: | :---: |
| [![Download](https://img.shields.io/badge/download-latest-blue.svg)](https://github.com/Steadfast5/Steadfast5/releases/latest/download/Steadfast5.phar) | [![License](https://img.shields.io/badge/license-LGNU%20v3-blue.svg?style=flat-square)](https://github.com/Steadfast5/Steadfast5/blob/master/LICENSE) |

Click [here](https://github.com/IceCruelStuff/Steadfast5) to view other repository.

[![HitCount](http://hits.dwyl.com/Steadfast5/https://githubcom/Steadfast5/Steadfast5.svg)](http://hits.dwyl.com/Steadfast5/https://githubcom/Steadfast5/Steadfast5) [![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/Steadfast5/Steadfast5) [![GitHub release](https://img.shields.io/github/release/Steadfast5/Steadfast5.svg)](https://github.com/Steadfast5/Steadfast5/releases/latest)

## Credits

Most of the code was written by originally written by [MFDGaming](https://github.com/MFDGaming), [iAldrich23xX](https://github.com/iAldrich23xX), and [LiTEKPE](https://github.com/LiTEKPE).

## Introduction

Steadfast5 is a project for backporting new Minecraft: Bedrock Edition changes to older PocketMine-MP versions for better stability and performance, while retaining as many features from the new PocketMine-MP versions as possible.

## Known bugs

- Players don't fall out of the world naturally, you'll want to handle PlayerMoveEvent as needed to kill them.

### TODO List
- [ ] Improve EntityAI
- [ ] Improve redstone
- [ ] Improve level generation
- [ ] Add level formats

## Installation

**The steps below will only work if you have Git installed. If you don't, follow [these](https://github.com/Steadfast5/Steadfast5/wiki/Installing-without-Git#installation) instructions**

### Installing on Windows

To install on Windows, please follow these instructions.

1) Open Git and type in `git clone https://github.com/Steadfast5/Steadfast5.git` 
2) Run the `start.cmd` file located in your server folder. It will automatically download PHP binary.
3) After running `start.cmd`, you have to complete the setup wizard. When you are finished all these steps, the server should start up.

### Installing on Linux/MacOS

To install on Linux/MacOS, please follow these instructions.
1) Open command line and type in `git clone https://github.com/Steadfast5/Steadfast5.git`. Then navigate to `Steadfast5` directory using command line. You can also download the zip file from GitHub and extract it.
2) Run command `./installer.sh`. If successful, this will create a `bin` folder with a special PHP build in it. After, run command `./start.sh` and the server should start up.

### License

```
   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Lesser General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
```
