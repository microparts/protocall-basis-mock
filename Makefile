IMAGE = microparts/protocall-mock-scratch
VERSION = latest

image:
	docker build -t $(IMAGE):$(VERSION) .

push:
	docker push $(IMAGE):$(VERSION)

all: image push
