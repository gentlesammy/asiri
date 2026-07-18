# TODO: Implement Username Rep Generation Function

- [x] Add private method `generateRep($username)` to RegisteredUserController.php that extracts first 3 characters of username and concatenates a random 6-digit number.
- [x] Update the `store` method in RegisteredUserController.php to set 'rep' => $this->generateRep($request->username) during user creation.

# TODO: Fix WebRTC Video Call Issues on EC2
- [ ] Install Let's Encrypt SSL on Nginx (`sudo apt install certbot python3-certbot-nginx`).
- [ ] Get TURN server credentials (e.g., Twilio Network Traversal or Metered TURN).
- [ ] Update `rtcConfig` in `room.blade.php` to include the TURN server credentials alongside the STUN servers.
- [ ] (Alternative to above) Install `coturn` on EC2 for self-hosted TURN.
- [ ] If self-hosting TURN, open AWS Security Group ports: TCP/UDP 3478, TCP/UDP 5349, and UDP 49152-65535.
- [ ] Refactor signaling from `setInterval` HTTP polling to WebSockets (using Laravel Reverb or Pusher) to ensure rapid ICE candidate exchange.
