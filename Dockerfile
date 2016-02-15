FROM tutum/apache-php
RUN rm -fr /app
COPY phpapp /app
