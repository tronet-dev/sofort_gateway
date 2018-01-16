==Title==
trosofortueberweisung

==Author==
tronet GmbH

==Prefix==
tro

==Version==
7.0.2

==Link==
http://www.tronet.media

==Mail==
info@tro.net

==Description==
Module for integration of Sofortueberweisung-payment in Oxid.

==Extend==
*oxpaymentgateway
--executePayment
*oxorder
--finalizeOrder
--_executePayment
*payment
--validatePayment

==Requirements==
1. Oxid eshop in Version from 4.7.x /5.0.x
3. An active "Sofortgateway-project" at Sofort.com 

==Installation==
1. Copy contents of folder "/copy_this/" into your shop-root
2. Activate module "trosofortueberweisung" in your oxid-backend
3. Enter your Configuration-Key at Extensions->Modules->tronet Sofort.
4. Configure new payment "Sofortüberweisung" in your oxid backend (Payment-methods, Shipping-methods)

==Modules==
oxpaymentgateway => tronet/trosofortueberweisung/application/models/trosofortueberweisungoxpaymentgateway
oxorder => tronet/trosofortueberweisung/application/models/trosofortueberweisungoxorder
order => tronet/trosofortueberweisung/application/controllers/trosofortueberweisungorder
order_overview => tronet/trosofortueberweisung/application/controllers/admin/trosofortueberweisungorder_overview
payment => tronet/trosofortueberweisung/application/controllers/trosofortueberweisungpayment
oxbasket => tronet/trosofortueberweisung/application/models/trosofortueberweisungoxbasket
oxbasketitem => tronet/trosofortueberweisung/application/models/trosofortueberweisungoxbasketitem
navigation => tronet/trosofortueberweisung/application/controllers/admin/trosofortueberweisung_navigation

==Libraries==
SofortLib-PHP-Payment-2.1.2 (/copy_this/tronet/trosofortueberweisung/library/)
