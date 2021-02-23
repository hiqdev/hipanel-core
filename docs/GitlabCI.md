# Gitlab CI configuration

All configurations where created with official documentation which you can check [here].

[here]: https://docs.gitlab.com/ee/ci

## Tests directory structure

- `.env` - environment variables configuration file
- `docker-compose.yml` - dc file to start application for testing
- `Dockerfile` - build configuration file to build project image
- other optional files which needed for testing (`_data` or `_output` for codecept, etc.)

## Configuring CI runner

To configure runner you have to check all these instructions

- You have to be sure that runner has access to push images to registry.
  You have to visit group members page, for example `https://git.hiqdev.com/groups/{group_name}/-/group_members`).
  Then ensure that user `robot` has Max role as `Developer`.
  
- For every project you have to enable ci runner.
  Visit page `https://git.hiqdev.com/{group_name}/{project_name}/-/settings/ci_cd`.
  Expand `Runners` block and click `Enable` for suggested runner.
  
- Also for every project CI you have to set auth configuration for runner.
  Visit page `https://git.hiqdev.com/{group_name}/{project_name}/-/settings/ci_cd`.
  Expand `Variables` block and set 3 variables: `CI_DOCKER_TOKEN, CI_DOCKER_USER and SSH_PRIVATE_KEY`.
  Values for this variables you can grab from existing CI configurations.
  
## .gitlab-ci.yml

- Main stages: `build`, `test` and `deploy`.
  But it can be customized for example build of hiapi.demo which has two builds and two deploy jobs - for `database` and `application`.
  
- Before script - install all software which are needed for testing
- If you want to run tests in docker environment don't forget to run `sleep 60` after `docker-compose up -d`.
   
  
## HiQDev insights

As we are using HIAPI_VAR dir to storage shared information as it has to be inside of repository for CI testing.
For example use this params for `.env`:

    HIAPI_VAR_DIR=@runtime

Also if some tests should be described for future realization but are not ready it can be marked as allowed to fail.
Add to `.gitlab-ci.yml` to job description `allow_failure: true`.

