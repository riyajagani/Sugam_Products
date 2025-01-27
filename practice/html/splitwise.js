// Show popups
document.getElementById('addMemberBtn').addEventListener('click', function() {
    document.getElementById('memberPopup').style.display = 'flex';
});

document.getElementById('addExpenseBtn').addEventListener('click', function() {
    document.getElementById('expensePopup').style.display = 'flex';
});

// Close popups
function closePopup(popupId) {
    document.getElementById(popupId).style.display = 'none';
}

// Save member
document.getElementById('saveMemberBtn').addEventListener('click', function() {
    const name = document.getElementById('memberName').value;
    const contact = document.getElementById('memberContact').value;

    if (name && contact) {
        const listItem = document.createElement('li');
        listItem.textContent = `Name: ${name}, Contact: ${contact}`;
        document.getElementById('members').appendChild(listItem);
        closePopup('memberPopup');
    } else {
        alert('Please enter both name and contact.');
    }
});

// Save expense
document.getElementById('saveExpenseBtn').addEventListener('click', function() {
    const amount = document.getElementById('expenseAmount').value;

    if (amount) {
        const listItem = document.createElement('li');
        listItem.textContent = `Amount: $${amount}`;
        document.getElementById('expenses').appendChild(listItem);
        closePopup('expensePopup');
    } else {
        alert('Please enter the amount.');
    }
});

