sub_path=$3
if [ -n $2 ]; then
	lang=$2/
else
	lang=""
fi
file_to_copy=js/jq.evnine.nav.js
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=js/jq.evnine.func.js
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=js/jq.evnine.debug.js
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=js/jq.include.js
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=test/ControllersPHPUnit.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=test/phpunit.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=evnine.php
[ ! -f "$1/${sub_path}evnine.controller.php" ] && [ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=evnine.controller.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/evnine.php" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=debug/evnine.debug.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=debug/evnine.views.generator.template.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=debug/evnine.views.generator.template.config.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=models/ModelsBitrix.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=models/ModelsBitrixInfoBlockParser.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=models/ModelsErrors.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=models/ModelsInfo.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=models/ModelsJoomla.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=models/ModelsMySQL.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=models/ModelsPHPUnit.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2
file_to_copy=models/ModelsValidation.php
[ -f "$1/${sub_path}$file_to_copy" ] && cp "Source/${lang}${file_to_copy}" "$1/${sub_path}$file_to_copy" >&2 && echo "update $1/${sub_path}$file_to_copy" >&2