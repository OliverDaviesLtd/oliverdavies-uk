# oliverdavies.uk

## Hosting

This site is hosted on a DigitalOcean droplet, which was created using [Ansible][] (see `tools/ansible/digitalocean.yml`).

[Ansible]: https://www.ansible.com

## Provisioning

To re-provision the server:

```bash
# Download the required roles
ansible-galaxy install -r tools/ansible/requirements.yml --force

# Run the provision playbook
ansible-playbook tools/ansible/provision.yml
```

### Deploying

Deployments for this site for managed with Ansible and [Ansistrano][].
Ansible Vault is used to manage sensitive information like database passwords.

Deployments are triggered automatically when changes are pushed to GitHub, and are performed automatically via GitHub Actions on each push to the `production` branch.

To run a deployment manually, run `ansible-playbook tools/ansible/deploy.yml --ask-vault-pass`.
The Vault password is stored in LastPass.

[Ansistrano]: https://ansistrano.com

#### Generating settings files

Production settings files are generated automatically during a deployment. This is done using the [opdavies.drupal_settings_files][drupal_settings_files] Ansible role, using variables from `tools/ansible/vars/deploy_vars.yml`, and performed during Ansistrano’s `After update code` build step.

[drupal_settings_files]: https://galaxy.ansible.com/opdavies/drupal_settings_files

## Migrating data into the website

To view the status of all the migrations:

    bin/drush.sh migrate:status

To run all the migrations:

    bin/drush.sh migrate:import --all

To run all the migrations and update the existing migrated content:

    bin/drush.sh migrate:import --all --update

## Talks ordering

In order to keep the talks page in the correct order, based on when the next time a talk is being given, the `created` date for the talk node is automatically updated on each save to match the date of the most future event for that talk.

The view is then sorting the talk nodes based on their `created` date.
