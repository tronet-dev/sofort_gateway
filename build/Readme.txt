==Title==
trosofortueberweisung

==Author==
tronet GmbH

==Prefix==
tro

==Version==
7.0.4

==Link==
http://www.tronet.media

==Mail==
vertrieb@tro.net

==Description==
Module for integration of Sofortueberweisung-payment in Oxid.

==Extend==
*oxbasketitem
--_setFromOrderArticle
*oxorder
--finalizeOrder
--_executePayment
--_updateOrderDate
--_checkOrderExist
*oxpaymentgateway
--executePayment
*payment
--validatePayment

==Requirements==
1. Oxid eshop in Version from 4.7.x /5.0.x
2. An active "Sofortgateway-project" at Sofort.com

==Installation==
1. Copy contents of folder "/copy_this/" into your shop-root
2. Activate module "trosofortueberweisung" in your oxid-backend
3. Enter your Configuration-Key at Extensions->Modules->tronet Sofort.
4. Configure new payment "Sofortüberweisung" in your oxid backend (Payment-methods, Shipping-methods)

==Modules==
oxpaymentgateway => tronet/trosofortueberweisung/application/models/trosofortueberweisungoxpaymentgateway
oxorder => tronet/trosofortueberweisung/application/models/trosofortueberweisungoxorder
oxorderarticles => tronet/trosofortueberweisung/application/models/trosofortueberweisungoxorderarticles
oxbasketitems => tronet/trosofortueberweisung/application/models/trosofortueberweisungoxbasketitems
order => tronet/trosofortueberweisung/application/controllers/trosofortueberweisungorder
payment => tronet/trosofortueberweisung/application/controllers/trosofortueberweisungpayment
navigation => tronet/trosofortueberweisung/application/controllers/admin/trosofortueberweisung_navigation

==Libraries==
SofortLib-PHP-Payment-2.1.2 (/copy_this/tronet/trosofortueberweisung/library/)
