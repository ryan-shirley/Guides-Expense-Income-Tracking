variable "resource_group" {
  type = object({
    name     = string
    location = string
  })
}

# variable "azure" {
#   type = object({
#     access_key      = string
#     subscription_id = string
#     client_id       = string
#     client_secret   = string
#     tenant_id       = string
#   })
# }

variable "environment" {
  type = object({
    location = string
    location_prefix = string
  })
}

variable "tags" {
  type = map(string)
}

# resource "random_pet" "env_username" {
#   length    = 4
#   separator = ""
# }

# resource "random_password" "env_password" {
#   length      = 16
#   min_upper   = 1
#   min_lower   = 1
#   min_numeric = 1
#   special     = false
# }

locals {
  # kv_name               = "integ-hub-${var.environment.name}"
  # sbus_name             = "integ-hub-${var.environment.name}-sbus"
  # shared_storage_name   = "fdinteghub${replace(var.environment.name, "-", "")}"
  # static_storage_name   = "fdinteghub${replace(var.environment.name, "-", "")}static"
  # app_service_plan_name = "integ-hub-${var.environment.name}-plan"
  app_service_plan_linux_name = "guides-planlinux"
  # signalrservice_name   = "integ-hub-${var.environment.name}-signalr"

  ##### NEW Infrastructure variables #####
  resource_group_name     = "az-${var.environment.location_prefix}-guides-rg-01"
  service_plan_linux_name = "az-${var.environment.location_prefix}-guides-asp-01"
}

terraform {
  required_providers {
    azurerm = {
      source  = "hashicorp/azurerm"
      version = "~> 2.78.0"
    }
    random = {
      source  = "hashicorp/random"
      version = "~> 3.1.0"
    }
    null = {
      source  = "hashicorp/null"
      version = "~> 3.1.0"
    }
  }
}

# Configure the Azure Provider
provider "azurerm" {
  features {}

  subscription_id = var.azure.subscription_id
  client_id       = var.azure.client_id
  client_secret   = var.azure.client_secret
  tenant_id       = var.azure.tenant_id
}

data "azurerm_client_config" "current" {}

###############################################################################
## Create a resource group
###############################################################################
resource "azurerm_resource_group" "resource_group" {
  name     = local.resource_group_name
  location = var.environment.location
  tags     = var.tags
}

###############################################################################
## App Service Plans
###############################################################################

# Create Free Plan - Linux
resource "azurerm_app_service_plan" "linux_plan" {
  name                = local.service_plan_linux_name
  location            = azurerm_resource_group.resource_group.location
  resource_group_name = azurerm_resource_group.resource_group.name
  tags                = var.tags

  sku {
    tier = "Free"
    size = "F1"
  }

  lifecycle {
    ignore_changes = [ kind ]
  }
}

###############################################################################
## App Services
###############################################################################

module "guides-development-linux" {
  source                            = "./modules/app-services/guides-development-linux"
  resource_group_name               = azurerm_resource_group.resource_group.name
  resource_group_location           = azurerm_resource_group.resource_group.location
  service_plan_id                   = azurerm_app_service_plan.linux_plan.id
  service_name                      = "guides-development"
  environment_location_prefix       = var.environment.location_prefix
  tags                              = var.tags
}


##############################################################################

# Output
output resource_group_name {
  value      = azurerm_resource_group.resource_group.name
  depends_on = [azurerm_resource_group.resource_group]
}