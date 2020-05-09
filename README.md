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

Deployments are triggered automatically when changes are pushed to GitHub, and are performed via [CircleCI][].

To run a deployment manually, run `ansible-playbook tools/ansible/deploy.yml --ask-vault-pass`.
The Vault password is stored in LastPass.

[Ansistrano]: https://ansistrano.com
[CircleCI]: https://circleci.com/gh/opdavies/oliverdavies-uk

## Migrating data into the website

To view the status of all the migrations:

    bin/drush.sh migrate:status

To run all the migrations:

    bin/drush.sh migrate:import --all

To run all the migrations and update the existing migrated content:

    bin/drush.sh migrate:import --all --update
