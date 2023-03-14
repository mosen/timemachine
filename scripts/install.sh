#!/bin/bash

# time machine controller
CTL="${BASEURL}index.php?/module/timemachine/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/timemachine" -o "${MUNKIPATH}preflight.d/timemachine"

# Only download mac_alias.zip if we don't already have it
if [ ! -f "${MUNKIPATH}mac_alias/alias.py" ]; then
	"${CURL[@]}" -s "${CTL}get_script/mac_alias.zip" -o "${MUNKIPATH}mac_alias.zip"
fi

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/timemachine"

	# Unzip the mac_alias.zip only if it exists
	if [ -f "${MUNKIPATH}mac_alias.zip" ]; then
		unzip -oqq "${MUNKIPATH}mac_alias.zip" -d "${MUNKIPATH}"
	fi

	# Set preference to include this file in the preflight check
	setreportpref "timemachine" "${CACHEPATH}timemachine.plist"

	# Delete the older style txt cache file
	if [[ -f "${MUNKIPATH}preflight.d/cache/timemachine.txt" ]] ; then
		rm -f "${MUNKIPATH}preflight.d/cache/timemachine.txt"
	fi

	# Delete the older timemachine.sh
	if [[ -f "${MUNKIPATH}preflight.d/timemachine.sh" ]] ; then
		rm -f "${MUNKIPATH}preflight.d/timemachine.sh"
	fi

	# Clean up mac_alias.zip only if it exists
	if [ -f "${MUNKIPATH}mac_alias.zip" ]; then
		rm -f "${MUNKIPATH}mac_alias.zip"
	fi

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/timemachine"
	rm -f "${MUNKIPATH}mac_alias.zip"

	# Signal that we had an error
	ERR=1
fi
