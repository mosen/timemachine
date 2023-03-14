#!/bin/bash

# Remove timemachine script
rm -f "${MUNKIPATH}preflight.d/timemachine"

# Remove timemachine.plist file
rm -f "${CACHEPATH}timemachine.plist"

# Remove mac_alias
rm -fr "${MUNKIPATH}mac_alias/"