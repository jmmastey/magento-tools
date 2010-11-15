#!/bin/bash
# Usage: ./install.sh /path/to/magento

# get magento path. works either from plugin directory or the one above it
STDIR=`pwd`
cd ../../../../../
MAGENTO=`pwd`
if [ 1 == $# ]; then MAGENTO=$1
elif [ ! -f "$MAGENTO/LICENSE.html" ]; then cd $STDIR; cd ../../../../; MAGENTO=`pwd`;
fi

COMPANY="%COMPANY%"
MOD="%PLUGIN%"
MODL="%PLUGINL%"
FRONTTHEME="default"

MODULES=$MAGENTO/app/etc/modules
LOCALE=$MAGENTO/app/locale/en_US
SRC=code/local/$COMPANY/$MOD

# file existence, part 1
if [ ! -f "$MAGENTO/LICENSE.html" ]; then echo "Unable to locate magento directory at $MAGENTO. Please enter a valid path to the magento installation"; exit;
elif [ ! -d $MODULES ] || [ ! -d $LOCALE ]; then echo "Unable to locate one of the required directories for module installation. Is $MAGENTO really the magento directory?"; exit;
elif [ ! -d "$MAGENTO/app/$SRC" ]; then echo "Couldn't find plugin source. Looking for it at \"$MAGENTO/app/$SRC\". Can't continue."; exit;
fi

VERSION="default"
if [ -f "$MAGENTO/LICENSE_EE.html" ]; then
    VERSION="enterprise"
    echo "Found Magento. Looks like you're using enterprise edition."
    echo ""
fi

#cd "app/design/frontend/${VERSION}"
#THEMES=`ls -1 | grep -v "default$" | sed -e s/$/,\ / | xargs echo`
#echo "Default frontend theme is: ${FRONTTHEME}. Press enter for this theme or enter another one from [${THEMES} default]:"
#read THEME

#if [ $THEME ] && [ -d $THEME ]; then
#    FRONTTHEME=$THEME
#elif [ $THEME ]; then
#    echo "You entered an invalid theme ${THEME}. Can't continue."
#    exit
#fi

#echo "Using ${FRONTTHEME} as frontend theme."
#echo ""

#file existence, part 2
#ALAYOUT="$MAGENTO/app/design/adminhtml/default/default/layout"
#FLAYOUT="$MAGENTO/app/design/frontend/${VERSION}/${FRONTTHEME}/layout"
#ATEMPLATE="$MAGENTO/app/design/adminhtml/default/default/template"
#FTEMPLATE="$MAGENTO/app/design/frontend/${VERSION}/${FRONTTHEME}/template"
#if [ ! -d  ${ALAYOUT} ]; then echo "Can't find directory ${ALAYOUT} for admin layout. Unable to continue."; exit;
#elif [ ! -d  ${FLAYOUT} ]; then echo "Can't find directory ${FLAYOUT} for frontend layout. Unable to continue."; exit;
#elif [ ! -d  ${ATEMPLATE} ]; then echo "Can't find directory ${ATEMPLATE} for admin templates. Unable to continue."; exit;
#elif [ ! -d  ${FTEMPLATE} ]; then echo "Can't find directory ${FTEMPLATE} for frontend templates. Unable to continue."; exit;
#fi

echo "Found necessary directories. Installing $MOD at $MAGENTO"

# proceed with install
cd ${MODULES}
if [ -f "${COMPANY}_${MOD}.xml" ] || [ -h "${COMPANY}_${MOD}.xml" ]; then
    rm "${COMPANY}_${MOD}.xml";
fi
ln -s "../../$SRC/${COMPANY}_${MOD}.xml" .
echo "Added module XML"

#cd ${LOCALE}
#if [ -f "${COMPANY}_${MOD}.csv" ] || [ -h "${COMPANY}_${MOD}.csv" ]; then
#    rm "${COMPANY}_${MOD}.csv"
#fi
#ln -s "../../$SRC/${COMPANY}_${MOD}.csv" .
#echo "Added localization file"
#
#cd ${ALAYOUT}
#if [ -f "$MODL.xml" ] || [ -h "$MODL.xml" ]; then
#    rm "$MODL.xml"
#fi
#ln -s "../../../../../$SRC/app/design/adminhtml/layout/$MODL.xml" .
#
#cd ${FLAYOUT}
#if [ -f "$MODL.xml" ] || [ -h "$MODL.xml" ]; then
#	rm "$MODL.xml"
#fi
#ln -s "../../../../../$SRC/app/design/frontend/layout/$MODL.xml" .
#echo "Added $MODL layouts"
#
#cd ${ATEMPLATE}
#if [ -h "$MODL" ] || [ -d "$MODL" ]; then
#    rm -rf "$MODL"
#fi
#ln -s "../../../../../$SRC/app/design/adminhtml/template/$MODL" .
#
#cd ${FTEMPLATE}
#if [ -h "$MODL" ] || [ -d "$MODL" ]; then
#    rm -rf "$MODL"
#fi
#ln -s "../../../../../$SRC/app/design/frontend/template/$MODL" .
#echo "Added template directories"

# should probably let the user know...
echo "Installation script completed. Make sure to disable/refresh cache to view changes."
