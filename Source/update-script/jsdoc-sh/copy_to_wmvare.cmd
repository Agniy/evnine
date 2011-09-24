set VMWARE=N:\
copy js\jq.evnine.nav.js %VMWARE%jq.evnine.nav.js
copy js\jq.evnine.func.js %VMWARE%jq.evnine.func.js 
copy js\jq.index.js %VMWARE%jq.index.js
copy js\jq.evnine.debug.js %VMWARE%jq.evnine.debug.js
echo update > %VMWARE%evnine\flag
sleep 20000
echo update >"xrefresh_flag\xrefresh.php"
