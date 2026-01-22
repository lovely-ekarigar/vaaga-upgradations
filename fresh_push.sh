#!/bin/bash
# Script to push repository with fresh history (no large file history)

echo "🚀 Starting fresh push to GitHub..."
echo ""
echo "⚠️  This will create a fresh commit with all current code"
echo "   Old commit history will be replaced on remote"
echo ""

# Check current branch
CURRENT_BRANCH=$(git branch --show-current)
echo "Current branch: $CURRENT_BRANCH"

# Create orphan branch (no history)
echo ""
echo "📦 Creating fresh branch..."
git checkout --orphan fresh-main

# Add all files
echo "📝 Adding all files..."
git add .

# Commit everything
echo "💾 Committing..."
git commit -m "Initial commit: vaaga-upgradations - Laravel 12 with Vite"

# Push to main (force to replace history)
echo ""
echo "🚀 Pushing to GitHub (this may take a few minutes)..."
git push -f origin fresh-main:main

# Switch back to original branch
git checkout $CURRENT_BRANCH

echo ""
echo "✅ Done! Check GitHub: https://github.com/lovely-ekarigar/vaaga-upgradations"
echo ""
echo "To push other branches:"
echo "  git checkout upgradation-php-8-with-laravel-12"
echo "  git push origin upgradation-php-8-with-laravel-12"
echo ""
echo "  git checkout upgradtion-php8.1,laravel10"
echo "  git push origin upgradtion-php8.1,laravel10"
