#!/bin/bash
# Script to remove large tracked files and then push to git

echo "⚠️  WARNING: This will remove large files from git tracking"
echo "Files will stay on your computer, but will be removed from git"
echo ""
read -p "Continue? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    exit 1
fi

echo "📦 Removing vendor/ from git tracking..."
git rm -r --cached vendor/ 2>/dev/null || echo "vendor/ not found or already removed"

echo "📦 Removing node_modules/ from git tracking..."
git rm -r --cached node_modules/ 2>/dev/null || echo "node_modules/ not found or already removed"

echo "📦 Removing public asset directories from git tracking..."
git rm -r --cached public/js/ public/css/ public/fonts/ 2>/dev/null || true
git rm -r --cached public/assets/ public/assets-rtl/ 2>/dev/null || true
git rm -r --cached public/frontend/ public/newassets/ 2>/dev/null || true
git rm -r --cached public/ng/ public/nglive/ 2>/dev/null || true
git rm -r --cached public/plugins/ public/storage1/ 2>/dev/null || true
git rm -r --cached public/upload/ public/uploads/ 2>/dev/null || true
git rm -r --cached public/live/ public/aff/ 2>/dev/null || true
git rm -r --cached public/icon/ public/images/ public/img/ 2>/dev/null || true
git rm -r --cached public/mentor/ public/photos/ 2>/dev/null || true

echo "✅ Files removed from git tracking"
echo ""
echo "📝 Staging .gitignore changes..."
git add .gitignore

echo ""
echo "✅ Ready to commit. Run these commands:"
echo "   git commit -m 'Remove large files from tracking and update .gitignore'"
echo "   git push --all"
echo ""
echo "⚠️  Note: This will reduce repository size significantly!"
