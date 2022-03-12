#!/bin/bash
# pull content to local for the current branch

# fetch current branch
CUR_BRANCH=$(git branch | grep '*' | awk -F ' ' '{print $2}')
# tip message
echo "current branch: ${CUR_BRANCH}"

# pull
git pull origin $CUR_BRANCH

# operate tip
if [[ $? -ne 0 ]]; then
	echo "pull fail"
else
	echo "pull success"
fi