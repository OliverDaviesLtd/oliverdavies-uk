# oliverdavies.uk

## Hosting

This site is hosted on a DigitalOcean droplet, which was created using [Ansible][] (see `tools/ansible/digitalocean.yml`).

[Ansible]: https://www.ansible.com

To download the required roles, run `ansible-galaxy install -r tools/ansible/requirements.yml`.

### Deploying

Deployments for this site for managed with Ansible and [Ansistrano][].
Ansible Vault is used to manage sensitive information like database passwords.

Deployments are triggered automatically when changes are pushed to GitHub, and are performed via [CircleCI][].

To run a deployment manually, run `ansible-playbook tools/ansible/deploy.yml --ask-vault-pass`.
The Vault password is stored in LastPass.

[Ansistrano]: https://ansistrano.com
[CircleCI]: https://circleci.com/gh/opdavies/oliverdavies-uk
