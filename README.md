# Address Manager
Welcome to https://geoserver.frans.ai (production)

Address Manager is a small Laravel application that lets a logged-in user:

- Capture and manage addresses
- Geocode those addresses asynchronously via a background queue
- Store latitude/longitude and show the location on a map in the UI

The app runs partly in Docker (database), with a Makefile providing nice shortcuts for common tasks.

---

## Installation

- composer install
- npm install
- docker-compose up

---

## Tech Stack

- **Backend:** Laravel
- **Frontend:** Inertia + Vue 3
- **Queue:** Laravel queue workers
- **Database:** MySQL (via Docker)
- **Environment:** Docker & Docker Compose, PHP
- **Tools:** Makefile helpers

---

## Prerequisites

You will need:

- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- Docker Compose (comes with recent Docker Desktop versions)
- `make` (installed by default on macOS and most Linux distros)
- PHP8 and Nodejs

---

## 1. Clone the Repository

```bash
git clone https://github.com/franslourens/address-manager.git
cd address-manager