const fs = require('fs');
const path = require('path');

const currentProject = 'c:\\Projects\\vaagaacademy';
const oldProject = 'C:\\Users\\malik\\Downloads\\_public_html_vaaga_mock';

function getAllFiles(dirPath, arrayOfFiles = [], extensions = null) {
    if (!fs.existsSync(dirPath)) {
        return arrayOfFiles;
    }

    const files = fs.readdirSync(dirPath);

    files.forEach(function(file) {
        const fullPath = path.join(dirPath, file);
        if (fs.statSync(fullPath).isDirectory()) {
            arrayOfFiles = getAllFiles(fullPath, arrayOfFiles, extensions);
        } else {
            if (extensions) {
                const ext = path.extname(file).toLowerCase();
                if (extensions.includes(ext)) {
                    const relPath = path.relative(dirPath, fullPath).replace(/\\/g, '/');
                    arrayOfFiles.push(relPath);
                }
            } else {
                const relPath = path.relative(dirPath, fullPath).replace(/\\/g, '/');
                arrayOfFiles.push(relPath);
            }
        }
    });

    return arrayOfFiles;
}

function getRelativeFiles(baseDir, subDir, extensions = null) {
    const fullPath = path.join(baseDir, subDir);
    return getAllFiles(fullPath, [], extensions);
}

function compareArrays(oldArr, currentArr) {
    const missing = oldArr.filter(x => !currentArr.includes(x)).sort();
    const extra = currentArr.filter(x => !oldArr.includes(x)).sort();
    return { missing, extra };
}

console.log('='.repeat(80));
console.log('RESOURCES DIRECTORY COMPARISON');
console.log('='.repeat(80));

// Check if directories exist
console.log('\nChecking project paths...');
console.log(`Current project: ${currentProject} - ${fs.existsSync(currentProject) ? 'EXISTS' : 'NOT FOUND'}`);
console.log(`Old project: ${oldProject} - ${fs.existsSync(oldProject) ? 'EXISTS' : 'NOT FOUND'}`);

if (!fs.existsSync(currentProject) || !fs.existsSync(oldProject)) {
    console.error('ERROR: One or both project directories not found!');
    process.exit(1);
}

let report = [];
report.push('='.repeat(80));
report.push('RESOURCES DIRECTORY COMPARISON REPORT');
report.push('='.repeat(80));
report.push('');

// 1. Blade View Files Comparison
console.log('\n' + '='.repeat(80));
console.log('1. BLADE VIEW FILES COMPARISON');
console.log('='.repeat(80));

const currentBlade = getRelativeFiles(currentProject, 'resources/views', ['.php']);
const oldBlade = getRelativeFiles(oldProject, 'resources/views', ['.php']);

// Filter for .blade.php files only
const currentBladeFiles = currentBlade.filter(f => f.endsWith('.blade.php'));
const oldBladeFiles = oldBlade.filter(f => f.endsWith('.blade.php'));

console.log(`\nCurrent Project: ${currentBladeFiles.length} blade files`);
console.log(`Old Project: ${oldBladeFiles.length} blade files`);

const bladeComparison = compareArrays(oldBladeFiles, currentBladeFiles);

console.log(`\n--- MISSING IN CURRENT PROJECT (${bladeComparison.missing.length} files) ---`);
bladeComparison.missing.forEach(f => console.log(`  - ${f}`));

console.log(`\n--- NEW IN CURRENT PROJECT (${bladeComparison.extra.length} files) ---`);
bladeComparison.extra.slice(0, 30).forEach(f => console.log(`  - ${f}`));
if (bladeComparison.extra.length > 30) {
    console.log(`  ... and ${bladeComparison.extra.length - 30} more files`);
}

report.push('MISSING BLADE VIEW FILES:');
report.push('-'.repeat(80));
bladeComparison.missing.forEach(f => report.push(`  ${f}`));
report.push(`\nTotal: ${bladeComparison.missing.length} files\n`);

// 2. JavaScript Assets Comparison
console.log('\n' + '='.repeat(80));
console.log('2. JAVASCRIPT ASSETS COMPARISON');
console.log('='.repeat(80));

const currentJs = getRelativeFiles(currentProject, 'resources', ['.js']);
const oldJs = getRelativeFiles(oldProject, 'resources', ['.js']);

console.log(`\nCurrent Project: ${currentJs.length} JS files`);
console.log(`Old Project: ${oldJs.length} JS files`);

const jsComparison = compareArrays(oldJs, currentJs);

console.log(`\n--- MISSING IN CURRENT PROJECT (${jsComparison.missing.length} files) ---`);
jsComparison.missing.forEach(f => console.log(`  - ${f}`));

console.log(`\n--- NEW IN CURRENT PROJECT (${jsComparison.extra.length} files) ---`);
jsComparison.extra.forEach(f => console.log(`  - ${f}`));

report.push('MISSING JS ASSETS:');
report.push('-'.repeat(80));
jsComparison.missing.forEach(f => report.push(`  ${f}`));
report.push(`\nTotal: ${jsComparison.missing.length} files\n`);

// 3. CSS/SASS Assets Comparison
console.log('\n' + '='.repeat(80));
console.log('3. CSS/SASS ASSETS COMPARISON');
console.log('='.repeat(80));

const currentCss = getRelativeFiles(currentProject, 'resources', ['.css', '.scss', '.sass']);
const oldCss = getRelativeFiles(oldProject, 'resources', ['.css', '.scss', '.sass']);

console.log(`\nCurrent Project: ${currentCss.length} CSS/SCSS/SASS files`);
console.log(`Old Project: ${oldCss.length} CSS/SCSS/SASS files`);

const cssComparison = compareArrays(oldCss, currentCss);

console.log(`\n--- MISSING IN CURRENT PROJECT (${cssComparison.missing.length} files) ---`);
cssComparison.missing.forEach(f => console.log(`  - ${f}`));

console.log(`\n--- NEW IN CURRENT PROJECT (${cssComparison.extra.length} files) ---`);
cssComparison.extra.forEach(f => console.log(`  - ${f}`));

report.push('MISSING CSS/SASS ASSETS:');
report.push('-'.repeat(80));
cssComparison.missing.forEach(f => report.push(`  ${f}`));
report.push(`\nTotal: ${cssComparison.missing.length} files\n`);

// 4. Translation Files Comparison
console.log('\n' + '='.repeat(80));
console.log('4. TRANSLATION FILES COMPARISON');
console.log('='.repeat(80));

const currentLang = getRelativeFiles(currentProject, 'resources/lang', null);
const oldLang = getRelativeFiles(oldProject, 'resources/lang', null);

console.log(`\nCurrent Project: ${currentLang.length} translation files`);
console.log(`Old Project: ${oldLang.length} translation files`);

const langComparison = compareArrays(oldLang, currentLang);

console.log(`\n--- MISSING IN CURRENT PROJECT (${langComparison.missing.length} files) ---`);
langComparison.missing.slice(0, 40).forEach(f => console.log(`  - ${f}`));
if (langComparison.missing.length > 40) {
    console.log(`  ... and ${langComparison.missing.length - 40} more files`);
}

console.log(`\n--- NEW IN CURRENT PROJECT (${langComparison.extra.length} files) ---`);
langComparison.extra.slice(0, 15).forEach(f => console.log(`  - ${f}`));
if (langComparison.extra.length > 15) {
    console.log(`  ... and ${langComparison.extra.length - 15} more files`);
}

report.push('MISSING TRANSLATION FILES:');
report.push('-'.repeat(80));
langComparison.missing.forEach(f => report.push(`  ${f}`));
report.push(`\nTotal: ${langComparison.missing.length} files\n`);

// Summary
console.log('\n' + '='.repeat(80));
console.log('SUMMARY');
console.log('='.repeat(80));
console.log(`\nMissing Blade Views: ${bladeComparison.missing.length}`);
console.log(`Missing JS Assets: ${jsComparison.missing.length}`);
console.log(`Missing CSS/SASS Assets: ${cssComparison.missing.length}`);
console.log(`Missing Translation Files: ${langComparison.missing.length}`);
console.log(`\nNew Blade Views: ${bladeComparison.extra.length}`);
console.log(`New JS Assets: ${jsComparison.extra.length}`);
console.log(`New CSS/SASS Assets: ${cssComparison.extra.length}`);
console.log(`New Translation Files: ${langComparison.extra.length}`);

report.push('');
report.push('='.repeat(80));
report.push('SUMMARY');
report.push('='.repeat(80));
report.push(`Missing Blade Views: ${bladeComparison.missing.length}`);
report.push(`Missing JS Assets: ${jsComparison.missing.length}`);
report.push(`Missing CSS/SASS Assets: ${cssComparison.missing.length}`);
report.push(`Missing Translation Files: ${langComparison.missing.length}`);
report.push(`New Blade Views: ${bladeComparison.extra.length}`);
report.push(`New JS Assets: ${jsComparison.extra.length}`);
report.push(`New CSS/SASS Assets: ${cssComparison.extra.length}`);
report.push(`New Translation Files: ${langComparison.extra.length}`);

// Save report
const reportPath = path.join(currentProject, 'resources_comparison_report.txt');
fs.writeFileSync(reportPath, report.join('\n'), 'utf8');
console.log(`\n\nDetailed report saved to: ${reportPath}`);
