<?php
// Repository Update
shell_exec('/usr/local/cpanel/bin/uapi VersionControl update name=repo_name repository_root=/home/username/repositories/repo_name branch=master source_repository=\'{"remote_name":"origin"}\'
');
// Deploy
shell_exec('/usr/local/cpanel/bin/uapi VersionControlDeployment create repository_root=/home/username/repositories/repo_name');
echo "done";
?>
