# uPort Wordpress Plugin
The uPort Wordpress Plugin will help developers easily utilize uPort's Decentralized Identity Platform using Wordpress, the world's most popular Open Source Content Management.

### Bridging The Gap Between Web 2.0 and Web 3.0
Did you know it has been reported that Wordpress powers over 25% of the Internet? That's a lot of websites. A vast majority of websites are built using PHP and popular content management systems like Wordpress and Drupal bu

The uPort Wordpress Bridge Plugin will (coming soon) allow Wordpress websites to easily integrate decentralized solutions like self-sovereign identity and Ethereum blockchain signing requests in just a few minutesl

# The Big Picture
uPort wants to support PHP, so we decentralized, self-sovereign identity solutions can be installed in popular Open Source Content Management Systems like Wordpress and Drupal.

## Feature Requirements
- [ ] Passwordless Authentication using uPort's Decentralized Identity Verification protocols
- [ ] Save registered user information: MNID, address, pushToken, etc... the user database
- [ ] Attestation requests sent to registered decentralized identities
- [ ] Blockchain transaction signing request sent to registered decentralized identities

## Code Requirements
- [ ] [PHP] Create `ES256K` PHP library
- [ ] [PHP] Create `did-jwt-php` PHP module matching the Javascript `did-jwt` API interface

## Administrator Feature Requirements 
- [ ] Save decentralized application SimpleSigner (private key) in database*
- [ ] Easily Add additional backend permissions to a registered self-sovereign identity

* In the future a **key vault** should be implemented for better private key management.

