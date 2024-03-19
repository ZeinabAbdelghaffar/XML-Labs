document.addEventListener('DOMContentLoaded', function () {
    fetch('Lab1_Part1.xml')
        .then(response => response.text())
        .then(xmlString => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlString, 'text/xml');
            const employees = xmlDoc.querySelectorAll('employee');
            const employeesContainer = document.getElementById('employees-container');
            employees.forEach((employee) => {
                const name = employee.querySelector('name').textContent;
                const email = employee.querySelector('email').textContent;
                const phones = Array.from(employee.querySelectorAll('phone')).map(phone => `${phone.getAttribute('type')}: ${phone.textContent}`).join(', ');
                const address = Array.from(employee.querySelectorAll('address > *')).map(node => `${node.tagName}: ${node.textContent}`).join(', ');

                const employeeDiv = document.createElement('div');
                employeeDiv.classList.add('employee');
                employeeDiv.innerHTML = `
                    <h3>${name}</h3>
                    <p>Email: ${email}</p>
                    <p>Phones: ${phones}</p>
                    <p>Address: ${address}</p>
                `;
                employeesContainer.appendChild(employeeDiv);
            });
        })
        .catch(error => console.error('Error fetching XML file:', error));
});