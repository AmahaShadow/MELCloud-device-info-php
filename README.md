# MELCloud-device-info-php

This script retrieves device data from MELCloud (Mitsubishi electric A/C Cloud) for export to cacti

Note that you must have connected your pump to the cloud. You should be able to query the device from the cloud before using this.

Based on the work of http://mgeek.fr/blog/un-peu-de-reverse-engineering-sur-melcloud who originally reversed engineer the API (french)

This only returns the Actual Temp, Set Temp, Actual Fan Speed, and Energy Consumption which were what I wanted to monitor, but there are a lot more data available. 

Published as is to help those that might need this.

This is not officially supported by Mitsubishi Electric, and is technically against their T&C. Consider this ressource for education purposes only.
It might also be illegal in some part of the world. Europe SHOULD be ok as reverse engeneering is considered fair-use for interface implemntation, debugging & error correction.

Also, please avoid cloberring the Mitsu json server or they might modify the api to stop this from working.
