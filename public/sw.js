self.addEventListener('push', function (event) {
  if (!(self.Notification && self.Notification.permission === 'granted')) {
    // notifications doest not support or permission not granted!
    return;
  }

  if (event.data) {
    var msg = event.data.json();
  

    var options = {
      body: msg.body,
      icon: msg.icon
    };

    if (msg.actions && msg.actions.length > 0) {
      options.actions = msg.actions;
    }

    event.waitUntil(self.registration.showNotification(msg.title, options));
  }
});

// open a new tab in browser and show notification info when click on that notification
self.addEventListener('notificationclick', function (event) {
  if (event.action.length > 0) {
    self.clients.openWindow(event.action);
  }
});
