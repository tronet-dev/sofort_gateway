# Sofort. by ![alt text](out/img/tronet.gif)

This repository contains the sources & documentation for the module tronet/trosofortueberweisung.

This file gives you a quick briefing about the module. A more detailed description is available in the file `/docs/readme_de.pdf`.

## DESCRIPTION

The OXID eShop interface for Sofort. extends an existing installation of the software OXID eShop by the
payment method Sofort.

OXID eShop is a software by OXID eSales GmbH, Freiburg, Germany. It is
available at [www.oxid-esales.com](http://www.oxid-esales.com).

Sofort. is an online payment service of SOFORT AG, Gauting. The
registration / establishment of the service is done at [www.sofort.com](http://www.sofort.com).
The SOFORT AG charges transaction fees for using the service.


## Prerequirements

* A merchant account at the SOFORT AG. You can create one [here](https://www.sofort.com/eng-DE/verkaeufer/su/e-payment-sofort-ueberweisung/)
* An installed OXID eShop 6.0.x

## Install

Install the `tronet/trosofortueberweisung` package using [composer](https://getcomposer.org/):
composer require tronet/trosofortueberweisung:^8.0

## Update

You can update SOFORT Überweisung and its dependencies by running `composer update`.

## Supported OXID eShop versions

|Module version| supported OXID eShop version
|:------------:|:---------------------------:
|8.0.12        | 6.0 - 6.3
|7.0.12        | 4.7 - 4.10

## FAQ

Head over to [https://www.tronet.media](https://www.tronet.media) and take a look at the FAQ’s.The FAQ’s are available
at `ONLINE-SHOP -> OXID Schnittstelle Sofortüberweisung`. The FAQ is available in german.
If your question cannot be answered, feel free to create a ticket at [https://service.tro.net/](https://service.tro.net/).
