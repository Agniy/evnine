@set $1=%~n1
@set $2=%~2
@set $3=%~3
@set PATH_TO_RU=ru
@set PATH_TO_EN=en
if -%4==- "%$3%cat" %$2%%$1%.php | "%$3%grep" -v ^.*\s*ru:\s*.* | "%$3%sed" s/[\/][^*][^*].*/\/**/ | "%$3%sed" s/[[:space:]]en:// > "%PATH_TO_EN%/%$2%%$1%.php" 
if -%4==- "%$3%cat" %$2%%$1%.php | "%$3%grep" -v ^.*\s*en:\s*.* | "%$3%sed" s/[\/][^*][^*].*/\/**/ | "%$3%sed" s/[[:space:]]ru:// > "%PATH_TO_RU%/%$2%%$1%.php" 
if not -%4==- "%$3%cat" %$2%%$1%.php | "%$3%grep" -v ^.*\s*ru:\s*.* | "%$3%sed" s/[\/][^*][^*].*/\/**/ | "%$3%sed" s/\([^*][[:space:]][^^@].*\)/\1^<br^>/ | "%$3%sed" s/[[:space:]]en:// > "%PATH_TO_EN%/%$2%%$1%.php"
if not -%4==- "%$3%cat" %$2%%$1%.php | "%$3%grep" -v ^.*\s*en:\s*.* | "%$3%sed" s/[\/][^*][^*].*/\/**/ | "%$3%sed" s/\([^*][[:space:]][^^@].*\)/\1^<br^>/ | "%$3%sed" s/[[:space:]]ru:// > "%PATH_TO_RU%/%$2%%$1%.php"