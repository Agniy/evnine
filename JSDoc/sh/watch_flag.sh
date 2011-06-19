if [ -f evnine/flag ] 
then
rm -f evnine/flag
$(./split_en_rus.sh jq.evnine.nav.js $)
bash jsrun.sh -t=/templates/jsdoc -d=/jsdoc-ru jq.evnine.nav.ru.js $
bash jsrun.sh -t=/templates/jsdoc -d=/jsdoc-en jq.evnine.nav.en.js $
fi
