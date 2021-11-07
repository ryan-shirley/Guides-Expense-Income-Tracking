local_rg_name=`echo "RG-guides" | sed 's/ //g'`

rm -rf terraform.tfvars

cp -r general.tfvars general_run.tfvars

sed -i 's#-RG-NAME-#'$local_rg_name'#g' general_run.tfvars
sed -i 's#-RG-LOCATION-#'"$rg_location"'#g' general_run.tfvars
sed -i 's#-LOCATION-PREFIX-#'$location_prefix'#g' general_run.tfvars
sed -i 's#-LOCATION-#'"$location"'#g' general_run.tfvars

cat general_run.tfvars > terraform.tfvars

rm -f general_run.tfvars