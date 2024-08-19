FROM ubuntu:24.04

SHELL ["/bin/bash", "-exou", "pipefail", "-c"]

RUN apt update \
    && apt upgrade -y \
    && apt install -y \
        curl \
        zip \
        unzip \
        mysql-client

RUN if [ "$(uname -m)" = "x86_64" ]; then \
        curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip" \
            && unzip awscliv2.zip \
            && ./aws/install; \
    elif [ "$(uname -m)" = "aarch64" ]; then \
        curl "https://awscli.amazonaws.com/awscli-exe-linux-aarch64.zip" -o "awscliv2.zip" \
            && unzip awscliv2.zip \
            && ./aws/install; \
    else \
        echo "Unsupported platform" && exit 1; \
    fi

ARG WWWUSER=1001
ARG WWWGROUP=1001

# create group and user "scheduler"
RUN groupadd -g $WWWGROUP scheduler || true \
    && useradd -ms /bin/bash --no-log-init --no-user-group -g $WWWGROUP -u $WWWUSER scheduler

# set the working directory
WORKDIR /home/scheduler

# copy the entrypoint script
COPY containerize/scripts/aws-mysql-client.sh aws-mysql-client.sh

# set scripts to start the laravel octane app
RUN chmod +x aws-mysql-client.sh

# set the user
USER scheduler

ENTRYPOINT [" aws-mysql-client.sh"]

CMD ["/bin/bash"]
