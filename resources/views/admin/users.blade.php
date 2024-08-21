<x-admin-layout>
  <div id="user-list">

  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      async function getUsers() {
        try {
          const response = await fetch('/user-accounts');
          if (!response.ok) {
            throw new Error('Network response was not ok.');
          }
          const data = await response.json();
          const users = data.users;
          console.log(users);
          if (Array.isArray(users)) {
            const userListDiv = document.getElementById('user-list');
            users.forEach(user => {
              const userItem = document.createElement('div');
              userItem.textContent = user.name;
              userListDiv.appendChild(userItem);
            });
          } else {
            console.error('Expected an array of users but got:', users);
          }
        } catch (error) {
          console.error('Something went wrong', error);
        }
      }
      getUsers();
    });
  </script>
</x-admin-layout>
