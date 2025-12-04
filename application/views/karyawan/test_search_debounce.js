/*
 * TEST SCRIPT - Setup Bagian Karyawan Search Optimization
 * 
 * Cara menggunakan:
 * 1. Buka halaman Setup Bagian Karyawan
 * 2. Buka Browser Console (F12 > Console tab)
 * 3. Copy-paste fungsi test di bawah
 * 4. Jalankan test_debounce()
 */

// Test 1: Simulate rapid typing
console.log('=== TEST 1: Rapid Typing Simulation ===');
var testInput = document.getElementById('search_setup');
var typedChars = ['a', 'ab', 'abc', 'abcd'];
var delayMs = 100; // simulate typing speed

typedChars.forEach(function(char, index) {
  setTimeout(function() {
    testInput.value = char;
    var event = new Event('keyup', { bubbles: true });
    testInput.dispatchEvent(event);
    console.log('Typed: "' + char + '" at ' + (index * delayMs) + 'ms');
  }, index * delayMs);
});

// Test 2: Check debounce configuration
console.log('\n=== TEST 2: Debounce Configuration ===');
console.log('DEBOUNCE_DELAY:', DEBOUNCE_DELAY, 'ms');
console.log('MIN_SEARCH_LENGTH:', MIN_SEARCH_LENGTH, 'characters');
console.log('Current search input:', testInput.value);
console.log('Total karyawan loaded:', allKaryawan.length);
console.log('Currently filtered:', filteredKaryawan.length);
console.log('Currently selected:', selectedKaryawanSet.size);

// Test 3: Manual filter test
console.log('\n=== TEST 3: Manual Filter Test ===');
function testFilter(searchTerm) {
  console.log('Testing filter with: "' + searchTerm + '"');
  var startTime = performance.now();
  
  var results = allKaryawan.filter(function(kar) {
    return kar.nik.toLowerCase().includes(searchTerm) || 
           kar.nama_karyawan.toLowerCase().includes(searchTerm);
  });
  
  var endTime = performance.now();
  var duration = ((endTime - startTime) / 1000).toFixed(3);
  console.log('Results: ' + results.length + ' found in ' + duration + 's');
  
  return results;
}

// Run test filters
console.log('Searching for "1"...');
testFilter('1');

console.log('Searching for "10"...');
testFilter('10');

console.log('Searching for "john"...');
testFilter('john');

// Test 4: Selection tracking
console.log('\n=== TEST 4: Selection Tracking ===');
console.log('Selected IDs:', Array.from(selectedKaryawanSet));
console.log('Total selected:', selectedKaryawanSet.size);

// Test 5: Performance check
console.log('\n=== TEST 5: Page Performance ===');
console.log('DOM elements with id="search_setup":', document.getElementById('search_setup') ? 'FOUND' : 'NOT FOUND');
console.log('Search indicator element:', document.querySelector('.search-indicator') ? 'FOUND' : 'NOT FOUND');
console.log('Table tbody:', document.getElementById('tbody_karyawan') ? 'FOUND' : 'NOT FOUND');
console.log('Action section:', document.getElementById('action-section') ? 'FOUND' : 'NOT FOUND');

console.log('\n=== ALL TESTS COMPLETED ===');
