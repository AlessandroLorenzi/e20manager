ALTER TABLE events CHANGE title title VARCHAR(100) NOT NULL;
ALTER TABLE events CHANGE start_time start_time INTEGER NOT NULL;

CREATE TABLE IF NOT EXISTS tickets (
    ticket_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    event_id INTEGER,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) NOT NULL,
    max_tickets INTEGER,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE
);
