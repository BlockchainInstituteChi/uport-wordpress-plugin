# uPort Wordpress Plugin
### Bridging The Gap Between Web 2.0 and Web 3.0
Did you know it has been reported that Wordpress powers over 25% of the Internet? That's a lot of websites. A vast majority of websites are built using PHP and popular content management systems like Wordpress and Drupal bu

The uPort Wordpress Bridge Plugin will allow Wordpress websites to easily integrate decentralized solutions like self-sovereign identity and Ethereum blockchain signing requests in just a few minutes by utilzing the uPort self-sovereign identity platform.

**Just getting started with the Ethereum and Web 3.0?** We recommend reviewing the following resources to get a better understanding of Web 3.0 and why it's important for application developers.

# The Big Picture
The uPort Wordpress Plugin is currently in active development. The uPort team is primarily focused on crafting the decentralized identity specifications and protocols in addition to ready-to-go Javascript libraries. However, to make Web 3.0 technology more readily available, we're interested in supporting community project, like the uPort Wordpress Plugin, to help bring self-sovereign identity to as many people as possible.

## Feature Requirements
- [ ] Passwordless Login
- [ ] Attestation requests to registered decentralized identity
- [ ] Blockchain transaction signing request to registered decentralized identity
- [ ] Query ERC780 claims registry using registered decentralized identity address 
- [ ] Save registered user information: MNID, address, pushToken, etc... in database

## Code Requirements
- [ ] [PHP] Create `ES256K` PHP library
- [ ] [PHP] Create `did-jwt-php` PHP module matching the Javascript `did-jwt` API interface

## Administrator Requirements 
- [ ] Save Decentralized Application Private Key in Database
- [ ] Save list of ERC20 smart contracts
- [ ] Add additional permissions to a registered self-sovereign identity


### Passwordless Login
A core solution provided by uPort is single sign on. Decentralized identities can login to applications using cryptographically verifiable credentials - replacing the requirement for email(username) and passwords to authenticate a user.

With the advent of the decentralized solutions like the Ethereum Blockchain and Interplanetary File System (IPFS) entirely new categories of Internet applications can be created. Ethereum can be considered a universal state management system. IPFS is a decentralized file storage system. Ethereum and IPFS together create fascinating opportunities for developers to build a truly decentralized Internet.

First, let's start with brief overview of how decentralized authentication actually works.

uPort is a platform to create decentralized identities and applications. We use the Ethereum blockchain (and smart contracts) to create a public registry of decentralized identities. 

The ERC780 specification originally published by uPort's @oed is the smart contract specification used to publically register decentralized identities and applications. The Ethereum Claims Registry (ECR) allows persons, smart contracts, and machines to issue claims about each other, as well as self issued claims.

A claim has 4 fields:

- Issuer - who makes the claim (msg.sender in Ethereum terms)
- Subject - who is the claim about (another ethereum address)
- Key - what kind of claim is it (eg. "name", "credit score")
- Value - The actual value of the claim

uPort uses the Ethereum Claims Registry (ECR) in conjunction with IPFS to enable a protocol for private, but cryptographically verifiable credentials using a public/private keypairs as the core building a.k.a cryptographic primitives.

##### Example of **decentralized identity** saved on IPFS
```
{
  @context: "http://schema.org",
  @type: "Person,
  publicEncKey: "PrHPGZ8/1KvcbVCTCTWfhFugSPGmkh7ZNDmZ6VynvzA=",
  publicKey:"0x04951d21e8370e1dfbd19d9fe52bdd47c562afb04d5ac9cf554ea46a3511c5f2e91915209d0cb79584a28c1d4a3f943c7bd025ab567eeae37b1f6ac595beb0052d"
}

```

In addition to decentralized identities registered on the Ethereum blockchain and the InterPlanetary File System, it's also possible to register decentralized applications, which are responsible for `issuing` claims about decentralized identities.

##### Example of **decentralized application** saved on IPFS
```
{
  @type:"App"
  address:"2ooWkG721aG91HyfJqwktiFbJJPwatgRp7n"
  banner:{contentUrl: "/ipfs/QmW9N3AttXQ8LV9QaSwM8vW43Ubvq7e2BC3kWVvCvFBYmG"}
  description:"Create your own token. Send it to anyone for anything!"
  image:{contentUrl: "/ipfs/QmTscmFgZBXNe4zQpEZxJaQq4JLB2F6eYsevUtbFo1wWCv"}
  name:"Gluon"
  publicKey:"0x0409298a981b6841fe439039be9ac9e55a9f496c0fb4fa5679ce7614027196e591e1c9610c3e21c53f542b2b9cc52d30048a5c1b2818172fddeef47525a35b0b78"
  url:"https://gluon.space"
}
```

By registering self-sovereign identities and application public keys on in public (and decentralized) environment, a protocol for passing credentials (personal data and information) is established. This credential sharing protocol is the mechanism for replacing traditional login methods requiring username(email) and passwords. Instead of using the traditional authentications methods, uPort leverages the cryptographic primitives and universal state management system provided by Ethereum to create an arguably much more simple and secure method for users of the Internet to authenticate themselves on both applications and decentralized applications. 


## The "Hacky" Way - a naive implementation of
The uPort Wordpress Bridge needs to fulful the following critieria to be considered ready for non-beta testing purposes.

#### Project Goals

- [ ] Feature - Authenticated users can save ERC20 smart contracts to either localstorage/database.

#### Required Deliverables


## The Plugin Specification
The uPort Wordpress Plugin manages communication requests from sever, browser and smartphone.

1. [Browser/Server] Initialize Login Request by sending request from Browser to Server
2. [Server/Browser] Generate Credentials Request and send verified JWT and UUID to Browser
3. [Browser] Display QR Code
4. [Browser/Smartphone] Scan QR Code using uPort Mobile App/SDK
5. [Browser] Start polling Wordpress server endpoint using UUID as identifier
6. [Smartphone/Server] Approve Login request and send credentials to callback URL
7. [Server] Confirm requested credentials and send response to Browser polling for response
8. [Browser/Server] Authenticate Login Session 

A Login button should replace the default the email/password authentication fields. When the login button is clicked a request will be sent from the Wordpress Frontend to the Wordpress backend using Ajax. When the Wordpress server receives the login request from the Wordpress Frontend, a credential request will be generated (privately signed) and issued back to the Frontend.

When a new credential request is generated using the `uport-php` library a unique identifier (random string) using `uuid` will also be generated. 

The `uuid` should be saved in a database table and also included in the `callback` url.

Javascript Example

```
import { Credentials, SimpleSigner } from 'uport'
const setttings = { networks, address: '5A8bRWU3F7j3REx3vkJ...', signer: new SimpleSigner(process.env.PRIVATE_KEY)}
const credentials = new Credentials(settings)

const requqest = { 
  requested: ['name', 'country'],
  callbackUrl: 'https://myserver.com/api/authentication',
  notifications: true 
}

credentials.createRequest(req).then(jwt => {

 })
 ```

Upon arrival of the privately signed credential request, the Wordpress Frontend will display a QR code for scanning by the uPort Smartphone Application. The uPort Mobile App (or SDK enabled mobile app) will include a `callback` URL, which is called once the credential request is `Approved` within the uPort Mobile App. A response object containing the requested credentials will be sent to the Wordpress servers.



## Technical Information
The uPort Wordpress Bridge should include both "centralized" and "decentralized" features.

#### Infrastructure - Passwordless Login
The authentication system should utilize uPort's attestation features to pass login credentials from the uPort mobile app to the Wordpress Frontend. The login credentials will be passed to Wordpress's default email/password authentication system. 

- [ ] Login Attestation Request from uPort Mobile App 
- [ ] Request Authentication information and send to default Wordpress Email/Password Authentication
- [ ] Request PushToken from the Decentralied Identity and saved in the Wordpress Database associated with new registered user

##### Future Bounty Candidate
uPort provides a PushToken (via a Firebase infrastructure), which allows applications to send attestation and transaction signing requesting directly to the uPort Mobile Application. This could be used by administrators to request additional information i.e. during an event registration process or during a checkout process at a later time.

If website administrators/developers wanted to utilize the PushToken capabilities it will be important to also create a Wordpress Plugin Dashboard Plugin that allows to manually or programatically interact with a decentralized identity.

#### Feature - Save List of Smart Contracts
The most common use case for the Ethereum Blockchain is ERC20 tokens. MetaMask provides a fantastic Browser Extension so everyone can quickly and easily interact with the Ethereum Blockchain, primarily to purchase ERC20 tokens or interact with CryptoKitties.

uPort imagines a world where tokens will be a common incentive mechanism across a variety of Internet applications. By helping adminstrators and website builders incorporate new decentralized features (like token incentivizes) into existing applications, it will be easier to help a broader audience understand the value of decentralized blockchains.

- [ ] Field To Add ERC20 Token Smart Contract Address
- [ ] Save Item in Wordpress Database or LocalStorage (Requires Discussion)
- [ ] Delete Item in Wordpress Database or LocalStorage (Requires Discussion) 

#### Decentralized - ERC20 Token Balances
To highlight the capabilities decentralized application the plugin should include basic features for the currently most popular Ethereum Blockchain feature - ERC20 Tokens. The `uport-connect` Javascript library, which is required as a project dependency, includes a Web3 object, which can easily interact with the Ethereum blockchain using an Infura API endpoint.

To encourage experiments token mechanics uPort's wants to provide simple interface buttons that connect the ERC20 specification i.e `transfer`, `approve` and `transerFrom` to easily embedable interface components. uPort helps facilitate token mechanic experimentation by providing private key management within a smartphone application. Private key management within a smartphone application means users can interact with the Ethereum Blockchain using a variety interfaces (both digital and physical) and not just a local home computer with a Browser extension.

##### Outline
- [ ] Display token balances for the currently logged in decentralized identity.
- [ ] Token Transfer Form - ERC20.abi.transfer
- [ ] Token Transfer From Form - ERC20.abi.transferFrom
- [ ] Token Approve Form - ERC20.abi.approve

When the forms are submitted a transaction signing request should either be sent to the uPort Mobile App (if logged in) or a QR code should be generated and displayed (if NOT logged in), so uPort Mobile App can scan the QR code and confirm the transaction requst. In other words, submitting these forms do not require interacting with the Wordpress database - everything is done using the Ethereum Blockchain.

If you're unfamiliar with the ERC20 token specification please reference the following materials

- https://github.com/ConsenSys/Tokens/tree/master/contracts/eip20
- https://theethereum.wiki/w/index.php/ERC20_Token_Standard
- https://medium.com/@jgm.orinoco/understanding-erc-20-token-contracts-a809a7310aa5

## User Story

#### User
As a user I want to maintain data privacy by storing my personal data on my smartphone. Releasing my personal information to the world only when it is absolutely required. As a default I would like to maintain self-sovereignty and ownership of my data by default. Instead of trusting companies to manage my data, I would instead like to provide cryptographically verified attestations i.e. private data to websites which include guarantees for single-time use.

#### Developer
As a developer I want to test new decentralized applications solutions quickly and easily. Since Wordpress is the world's popular Open Source Content Management, it would be great if I could easily add uPort's single sign-on capabilities and web3 object with a single plugin, which integrated with Wordpress Authentication System and allowed users to sign Ethereum Blockchain Transactions directly from their smartphones or a similar interface.

For example, in the near future it should be possible to easily embed decentralized event regisration and buyer rewards programs, without requiring paying monthly subscriptions. Thanks to the Ethereum Blockchain (and other decentralized technologies) features that once required centralized organizations/companies to build and maintain can simply become the fabric of the Internet. Open Source Smart Contracts will be readily available for developers easily embed on any website, which will handle a number of currently centralized services. 

## Background
uPort wants to provide simple tools for new developers to experiment and tinker with decentralized applications. Decentralized solutions like event registration, loyalty rewards programs, and other Internet enhanced protocols. However, instead of relying on centralized applications to provide the code (if/else/loops/arrays) and systems maintenance (servers, database, network) for common features of the Internet we all know and love today, these day-to-day solutions be transformed into public utilities - lowering the cost for users, whether that's direct (payment) or indirect (personal information).

To help users get accustomed to using decentralized technologies, like the Ethereum blockchain, uPort would like to help build the simple use-cases and ready-to-go examples, so developers, website administrators and everyday people buidling websites using Wordpress, can use decentralized solutions as easily as their able to deploy the Wordpress CMS i.e. in a couple of clicks.