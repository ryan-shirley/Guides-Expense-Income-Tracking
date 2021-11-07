variable "resource_group_name" {}
variable "resource_group_location" {}
variable "service_plan_id" {}
variable "environment_location_prefix" {}
variable "service_name" {}

variable "tags" {
  type = map(string)
}

##############################################################################
# Guides Development (Linux)
##############################################################################

# Create App Service
resource "azurerm_app_service" "app_service_guides_development" {
  name                  = "az-${var.environment_location_prefix}-${var.service_name}-appservice-01"
  location              = var.resource_group_location
  resource_group_name   = var.resource_group_name
  app_service_plan_id   = var.service_plan_id
  tags                  = var.tags

  app_settings = {
    "APP_DEBUG" = true
    "APP_KEY" = ""
    "DB_CONNECTION" = "mongodb"
    "DB_HOST" = "mongodb+srv://USER:PASSWORD@HOST.DOMAIN.mongodb.net"
    "Environment" = "development"
    "SENTRY_ENVIRONMENT" = "azure-development"
    "SENTRY_LARAVEL_DSN" = ""
    "SENTRY_TRACES_SAMPLE_RATE" = 1
  }

#   site_config {
#     dotnet_framework_version = "v4.0"
#     remote_debugging_enabled = true
#     remote_debugging_version = "VS2019"
#   }

  depends_on = [
    var.service_plan_id,
  ]
}