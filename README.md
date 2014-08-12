# Munisense Zigbee Library

## PHP library for encoding and decoding ZigBee Frames
This library contains classes that map to the various ZigBee frames. Each of those classes
can be either constructed using a bytestring or using the getters/setters and static constructors.

### Using the library
#### Installation
The easiest way to use the library is to add it as dependency in the [composer.json](http://getcomposer.org) of your project. 

    "require": {
        "munisense/zigbee": "~2.4",
    }

Then run `composer update` and include the `vendor/autoload.php` in your project files, if not already.

#### Use the ZigBee library to unpack ZigBee frames
The library can be used to unpack a zigbee frame (for instance encoded in a base64 string) to the object model.

    $input = base64_decode("AP8AAAA=");
    $frame = new Munisense\Zigbee\ZCL\ZCLFrame($input);
    echo $frame;

gives

    ZCLFrame (length: 5)
    |- FrameControl     : 0b00000000
    |  |- FrameType     : Profile Wide (0x00)
    |  |- ManufIdPres   : Not Present (0x00)
    |  |- Direction     : Server->Client (0x00)
    |  `- DefaultResp   : Enabled (0x00)
    |- TransactionId    : 0x8e
    |- CommandId        : Read Attributes
    |- Payload (length: 2)
    `- Munisense\Zigbee\ZCL\General\ReadAttributesCommand (count: 1, length: 2)
       `- AttributeId: 0x0000

#### Use the ZigBee library to pack ZigBee frames
It is also possible to build a ZigBee frame using the objects, and extract the actual frame payload to send to the ZigBee network.

    $zcl = ReadAttributesCommand::construct([
      AttributeIdentifier::construct(0x02),
      AttributeIdentifier::construct(0x0809)
    ]);

    $output = $zcl->getFrame();

### Running the tests
To run the tests you need phpunit installed. Instead of downloading the library as requirement for a project using Composer (and Packagist) you should have a clone of the library itself. Make sure you've ran `composer install`.

After that it is as simple as calling `phpunit` in the root folder everytime you want to run the tests.

### Travis Continuous Integration
* Master [![Build Status](https://travis-ci.org/munisense/zigbee.svg?branch=master)](https://travis-ci.org/munisense/zigbee)
* Stable [![Build Status](https://travis-ci.org/munisense/zigbee.svg?branch=stable)](https://travis-ci.org/munisense/zigbee)

### Revision History
#### 2.4.0
Added ZDP Discovery Commands
* ExtendedSimpleDescReq and Rsp Command
* UserDescConf and Set Command

Added ZDP Network Commands
* MgmtBindReq, MgmtCacheReqCommand
* MmtLqiReq and Rsp Command, with NeighborDescriptor structure
* MmtRtgReq and Rsp Command, with RoutingDescriptor structure

#### 2.3.0
Minor code organisation changes and added more ZDP Discovery functionality.
* Renamed MuniZigbeeException to ZigbeeException
* Removed Munisense specific frames
* Added Node Descriptor + Commands
* Added Power Descriptor + Commands
* Added User Descriptor + Commands

#### 2.2.0
Added the following ZDP Commands:

* ActiveEP Req and Rsp
* IEEEAddr Req and Rsp
* NodeDesc Req
* NWKAddr Req and Rsp
* PowerDesc Req
* SimpleDesc Req and Rsp

### License

    Copyright 2014 Munisense BV
    
    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at
    
    http://www.apache.org/licenses/LICENSE-2.0
    
    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.

