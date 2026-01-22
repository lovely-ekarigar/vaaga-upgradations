/**
 * Backend entry point that combines before.js, app.js, and after.js
 * This matches the webpack.mix.js configuration
 */

// Load before.js first
import './backend/before.js';

// Load app.js
import './backend/app.js';

// Load after.js last
import './backend/after.js';
