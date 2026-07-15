#!/bin/bash

# Get current branch
CURRENT_BRANCH=$(git branch --show-current)

echo "🚀 Preparing deployment push for branch: $CURRENT_BRANCH..."

# Check git status
git status

# Ask to continue
read -p "Stage all changes and commit? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    git add .
    
    # Prompt for commit message
    echo "Enter commit message (press Enter for default):"
    read commit_message
    
    if [ -z "$commit_message" ]; then
        commit_message="Deploy update: $(date +'%Y-%m-%d %H:%M:%S')"
    fi
    
    echo "💾 Committing changes..."
    git commit -m "$commit_message"
    
    echo "📤 Pushing to origin/$CURRENT_BRANCH..."
    git push origin "$CURRENT_BRANCH"
    
    echo "✅ Pushed successfully! GitHub Action deployment has been triggered."
else
    echo "❌ Deployment cancelled."
fi
