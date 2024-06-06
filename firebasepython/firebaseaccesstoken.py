import firebase_admin
from firebase_admin import credentials

# Service account JSON key

class FirebaseManager:

    def getAceesToken(self):
        cred = {
            "type": "service_account",
            "project_id": "easyride-417501",
            "private_key_id": "718657fead5ff9addfbfa4c5e49f61ca5f4fe8fa",
            "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDdSioKxZ5B2Msk\nGdzbX4gvt6yup4gyImfLL75CpU8pnZ7K+5NuQ0u4pE0Qqcn7XvJOgF/t59a+w/3A\nG7j+LjwE8m3CGIaWMBSM7i84ANzWPoYWyHCjApjkTf4s1sUgqKbV+k6I2pR/8CxY\n72XmDvXKwx0b4ZVH9lhHtNYqByznRBe6IAIiYaUg4OJkNJbkNeDSnWsmCkc+8pHC\nUsy5gFtpFmkf8h+nBtyJAB2IYxFSC+mWRFZlWz4Yx90sS2CJiirMMdaDnbvO1L6A\n4hZdJ2hfm/3hMmi7gJUohutZng9F09wshQcz/PWGOh1Jt4D29QWPWtKFonuM60no\nqqacI3lvAgMBAAECggEAU1veNXuW74gKkflaHJtCknMWzh4W7IfQYJaGDdX22Z7i\nE+Wr9mPMUOw3iZjkGNh933t1Z4mnd+odmvH2gG2LN3PZS73waQAIpcQNroOIrP4V\nhdyQrZ9LV+lXX62xyWkum7l0PxJT0VP4aLIZyQ2GopBYVUnncZnHTI+/A0r7q8vj\nreg17x2/mhpPCzIPIXawibB78I6O1o8R++vJ9RitPe/9cCf/35pDvtgvF+il1Piw\nt8PhkHW6810zUbG4hFsfl76Ht9C9QCP4K+Ui5l9OziEgdU9Fvxqf++9PTUTTEH4+\ntd3IZF5jb8Xq4bmHjm2TYSZaR4E8BjUoZpGW5aSw5QKBgQD7LORxjojrU5QNpm/6\nyN10vA/EE53CtGlpNEA59KsI7WKxs3GzT5n+Za+8U0m6+YNj/vNsuh4Dts/V90su\nmPx9DgFv7YkOacz78KfyBB2c4NXOBuTMMuagx1+9xu7HY9V4UPmd0zuDHNNjU8vx\n2qY4m1RhDXrnuhR+HFrjlarAywKBgQDhilCUl6UpHGqDNcjw33++aezC7fWLAWII\njJo4NlEoauvrz0SLJLYRhgHwenLQcmZrXJDifVXCIWKi5cls/RlmRDm6hQH0TFUx\nIYCXEm8+hqvc3DLLh64I0pVRBU3Rhp0IZCYaWszy57sORPQAjf5oLHmX6owDGGwo\nsnnLoTDJbQKBgQDsbH07Rmcq2yjpEFknPhr13m8YqT4+H4N5O22ZH+w+IvRbQAUu\nOydAhoa8RcmI7YP3g+ut1CKcbF+Fj5h87BP9wkkoCfaQUpZYL1ZmP+l/2UJnA6Yp\nb2AWxpQgFjh8BkTRU6ciH8pMx71SKXY22RDNB6iNEhs2M7lGW3pR7ncjsQKBgEfG\nNI6VE8JZgKvw2dmNxqFaJDaEc7eg4QnHdOyenIU4QYvxkeaV8DrzYnqc/RzIyz8v\nXgw1xqzY4wLkDY3ZndOlsplg6pZMFHBmMP3ip/RF9zt599A9hWzZVxtJjNI4/JBH\nDrkkXESQ6j5IQz5J8cRFIwztX1E83G63HXtn+JahAoGAcq+JXxaRUfHGunapjHMM\nKJ+9zbtfn9wE9du+v3EnFSY+9+vIUGGCeWlUS3FEwELH2Lmu0AnyduVYf5mGa/fa\nSz6c9U78xU28IPXey+1btsOF1OL19j42lG6LzdSDR3g+SDU7Qbpmt2p6n4hP7BUl\nvyB7vJQjEs7AOOExS0e/SgM=\n-----END PRIVATE KEY-----\n",
            "client_email": "firebase-android@easyride-417501.iam.gserviceaccount.com",
            "client_id": "110347530116030972322",
            "auth_uri": "https://accounts.google.com/o/oauth2/auth",
            "token_uri": "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-android%40easyride-417501.iam.gserviceaccount.com",
            "universe_domain": "googleapis.com"
        }

        # Initialize the Firebase Admin SDK with the service account credentials
        cred_service = credentials.Certificate(cred)
        if not firebase_admin._apps:
            firebase_admin.initialize_app(cred_service)
        access_token = cred_service.get_access_token().access_token
        return access_token


  

# Now you can use the Firebase Admin SDK to interact with Firebase services
