<?php
// Include header
include_once "../layouts/master/header.php";
?>

<div class="flex h-screen bg-gray-100">
    <!-- Include sidebar -->
    <?php include_once "../layouts/components/sidebar.php"; ?>

    <div class="flex-1 flex flex-col ml-64">
        <!-- Include topbar -->
        <?php include_once "../layouts/components/topbar.php"; ?>

        <!-- Main content -->
        <div class="p-6 mt-16 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex flex-col space-y-4">
                    <!-- Header with search and add user button -->
                    <div class="flex justify-between items-center">
                        <div class="relative w-64">
                            <input type="text" id="searchInput" placeholder="Search User, Group and Role" class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <button id="addUserBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Add user
                        </button>
                    </div>

                    <!-- Filters -->
                    <div class="flex space-x-4">
                        <div class="w-40">
                            <label class="block text-sm text-gray-500 mb-1">Unit Pengelola</label>
                            <div class="relative">
                                <select id="departmentFilter" class="w-full appearance-none border border-gray-300 rounded-md py-2 pl-3 pr-8 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="production">Production</option>
                                    <option value="supply">Supply</option>
                                    <option value="design">Design</option>
                                    <option value="marketing">Marketing</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="w-40">
                            <label class="block text-sm text-gray-500 mb-1">Status</label>
                            <div class="relative">
                                <select id="statusFilter" class="w-full appearance-none border border-gray-300 rounded-md py-2 pl-3 pr-8 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="w-40">
                            <label class="block text-sm text-gray-500 mb-1">Approver</label>
                            <div class="relative">
                                <select id="approverFilter" class="w-full appearance-none border border-gray-300 rounded-md py-2 pl-3 pr-8 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="all">All</option>
                                    <option value="manager1">Manager 1</option>
                                    <option value="manager2">Manager 2</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <button id="filtersBtn" class="border border-gray-300 bg-white text-gray-700 px-4 py-2 rounded-md flex items-center mt-5">
                                <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                </svg>
                                Filters
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="w-6 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enterprise</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name user</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project Status</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="userTableBody">
                                <!-- Table rows will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-700 mr-2">Show</span>
                            <select id="perPage" class="border border-gray-300 rounded-md text-sm py-1 px-2">
                                <option value="12">12</option>
                                <option value="24">24</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                            </select>
                        </div>
                        <div class="flex items-center space-x-1" id="pagination">
                            <button class="px-2 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50" id="prevPage">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <!-- Page buttons will be added by JavaScript -->
                            <button class="px-2 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50" id="nextPage">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Menu Template (Hidden by default) -->
<div id="actionMenu" class="hidden absolute bg-white shadow-lg rounded-md py-1 w-40 z-50">
    <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="edit">
        <svg class="inline-block w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
        </svg>
        Edit user
    </button>
    <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="delete">
        <svg class="inline-block w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        Delete user
    </button>
    <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="audit">
        <svg class="inline-block w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
        </svg>
        Audit logs
    </button>
    <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" data-action="report">
        <svg class="inline-block w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        Reporting
    </button>
</div>

<!-- JavaScript for the page functionality -->
<script>
// Sample data for the table
const userData = [
    { id: 1, enterprise: 'Company name 1', phone: '+1 926 25 35 965', department: 'Commercial', name: 'Michael Jones', role: 'Manager', status: 'Active' },
    { id: 2, enterprise: 'Company name 2', phone: '+1 926 25 35 965', department: 'Production', name: 'Michael Jones', role: 'Manager', status: 'Inactive' },
    { id: 3, enterprise: 'Company name 3', phone: '+1 926 25 35 965', department: 'Supply', name: 'Michael Jones', role: 'Admin', status: 'Active' },
    { id: 4, enterprise: 'Company name 4', phone: '+1 926 25 35 965', department: 'Design', name: 'Michael Jones', role: 'Technical user', status: 'Inactive' },
    { id: 5, enterprise: 'Company name 5', phone: '+1 926 25 35 965', department: 'Marketing', name: 'Michael Jones', role: 'Manager', status: 'Inactive' },
    { id: 6, enterprise: 'Company name 6', phone: '+1 926 25 35 965', department: 'Production', name: 'Michael Jones', role: 'Admin', status: 'Active' },
    { id: 7, enterprise: 'Company name 7', phone: '+1 926 25 35 965', department: 'Supply', name: 'Michael Jones', role: 'Technical user', status: 'Inactive' },
    { id: 8, enterprise: 'Company name 8', phone: '+1 926 25 35 965', department: 'Design', name: 'Michael Jones', role: 'Manager', status: 'Active' },
    { id: 9, enterprise: 'Company name 9', phone: '+1 926 25 35 965', department: 'Commercial', name: 'Michael Jones', role: 'Technical user', status: 'Active' },
    { id: 10, enterprise: 'Company name 10', phone: '+1 926 25 35 965', department: 'Marketing', name: 'Michael Jones', role: 'Manager', status: 'Active' },
    { id: 11, enterprise: 'Company name 11', phone: '+1 926 25 35 965', department: 'Commercial', name: 'Michael Jones', role: 'Manager', status: 'Active' },
    { id: 12, enterprise: 'Company name 12', phone: '+1 926 25 35 965', department: 'Commercial', name: 'Michael Jones', role: 'Manager', status: 'Active' },
];

// Variables for pagination
let currentPage = 1;
let itemsPerPage = 12;
let filteredData = [...userData];

// DOM elements
const tableBody = document.getElementById('userTableBody');
const paginationContainer = document.getElementById('pagination');
const prevPageBtn = document.getElementById('prevPage');
const nextPageBtn = document.getElementById('nextPage');
const perPageSelect = document.getElementById('perPage');
const searchInput = document.getElementById('searchInput');
const departmentFilter = document.getElementById('departmentFilter');
const statusFilter = document.getElementById('statusFilter');
const approverFilter = document.getElementById('approverFilter');
const actionMenu = document.getElementById('actionMenu');

// Function to render table rows
function renderTable() {
    tableBody.innerHTML = '';
    
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedData = filteredData.slice(startIndex, endIndex);
    
    paginatedData.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-3 py-4 whitespace-nowrap">
                <input type="checkbox" class="user-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded">
            </td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.enterprise}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">${user.phone}</td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.department}</td>
            <td class="px-3 py-4 whitespace-nowrap">
                <a href="#" class="text-sm text-blue-600 hover:underline">${user.name}</a>
            </td>
            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${user.role}</td>
            <td class="px-3 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <span class="text-sm text-gray-900 mr-2">${user.status}</span>
                    <div class="relative inline-block w-10 align-middle select-none">
                        <input type="checkbox" id="toggle-${user.id}" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" ${user.status === 'Active' ? 'checked' : ''}>
                        <label for="toggle-${user.id}" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                </div>
            </td>
            <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button class="action-button text-gray-500 hover:text-gray-700" data-user-id="${user.id}">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
            </td>
        `;
        tableBody.appendChild(row);
    });
    
    updatePagination();
}

// Function to update pagination controls
function updatePagination() {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    
    // Clear existing page buttons
    const pageButtons = paginationContainer.querySelectorAll('.page-button');
    pageButtons.forEach(button => button.remove());
    
    // Add page buttons
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.classList.add('page-button', 'px-3', 'py-1', 'border', 'border-gray-300', 'rounded-md', 'text-sm');
        
        if (i === currentPage) {
            pageButton.classList.add('bg-blue-600', 'text-white');
        } else {
            pageButton.classList.add('hover:bg-gray-50');
        }
        
        pageButton.textContent = i;
        pageButton.addEventListener('click', () => {
            currentPage = i;
            renderTable();
        });
        
        // Insert before the next button
        paginationContainer.insertBefore(pageButton, nextPageBtn);
    }
    
    // Update prev/next buttons state
    prevPageBtn.disabled = currentPage === 1;
    nextPageBtn.disabled = currentPage === totalPages;
}

// Function to filter data
function filterData() {
    const searchTerm = searchInput.value.toLowerCase();
    const departmentValue = departmentFilter.value;
    const statusValue = statusFilter.value;
    
    filteredData = userData.filter(user => {
        // Search filter
        const matchesSearch = 
            user.enterprise.toLowerCase().includes(searchTerm) ||
            user.name.toLowerCase().includes(searchTerm) ||
            user.role.toLowerCase().includes(searchTerm);
        
        // Department filter
        const matchesDepartment = departmentValue === 'all' || user.department.toLowerCase() === departmentValue;
        
        // Status filter
        const matchesStatus = statusValue === 'all' || user.status.toLowerCase() === statusValue;
        
        return matchesSearch && matchesDepartment && matchesStatus;
    });
    
    currentPage = 1;
    renderTable();
}

// Event listeners
prevPageBtn.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderTable();
    }
});

nextPageBtn.addEventListener('click', () => {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderTable();
    }
});

perPageSelect.addEventListener('change', () => {
    itemsPerPage = parseInt(perPageSelect.value);
    currentPage = 1;
    renderTable();
});

searchInput.addEventListener('input', filterData);
departmentFilter.addEventListener('change', filterData);
statusFilter.addEventListener('change', filterData);
approverFilter.addEventListener('change', filterData);

// Handle action menu
document.addEventListener('click', (e) => {
    if (!e.target.closest('.action-button') && !e.target.closest('#actionMenu')) {
        actionMenu.classList.add('hidden');
    }
});

document.addEventListener('click', (e) => {
    const actionButton = e.target.closest('.action-button');
    if (actionButton) {
        const userId = actionButton.dataset.userId;
        const rect = actionButton.getBoundingClientRect();
        
        actionMenu.style.top = `${rect.bottom + window.scrollY}px`;
        actionMenu.style.left = `${rect.left - 120 + window.scrollX}px`;
        actionMenu.classList.remove('hidden');
        
        // Store the current user ID for action handlers
        actionMenu.dataset.userId = userId;
        
        e.stopPropagation();
    }
});

// Action menu button handlers
document.querySelectorAll('#actionMenu button').forEach(button => {
    button.addEventListener('click', (e) => {
        const action = button.dataset.action;
        const userId = actionMenu.dataset.userId;
        
        // Handle different actions
        switch(action) {
            case 'edit':
                console.log(`Edit user ${userId}`);
                break;
            case 'delete':
                console.log(`Delete user ${userId}`);
                break;
            case 'audit':
                console.log(`View audit logs for user ${userId}`);
                break;
            case 'report':
                console.log(`Generate report for user ${userId}`);
                break;
        }
        
        actionMenu.classList.add('hidden');
    });
});

// Toggle switch styling
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .toggle-checkbox:checked {
            right: 0;
            border-color: #68D391;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #68D391;
        }
    </style>
`);

// Initialize the table
renderTable();
</script>

<?php
// Include footer
include_once "../layouts/master/footer.php";
?>