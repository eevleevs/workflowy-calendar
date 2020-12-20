# Installing
- edit credentials.php
- docker secret create workflowy_credentials_1
- restore credentials.php
- docker build -t workflowy_backup .
- docker service create --name workflowy_backup --secret workflowy_credentials_1 -v BACKUP_DIR:/app/data workflowy_backup