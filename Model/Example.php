<?php
    class ErrorLog extends StorageItem
    {

        /**
         * @Required
         * @Numeric
         *
         * @var number
         */
        public $datetime;

        /**
         * @Required
         * @Word
         * @Size(min=1, max=400)
         *
         * @var string
         */
        public $project;

        /**
         * @Required
         * @Word
         * @Size(min=1, max=1400)
         *
         * @var string
         */
        public $message;

        /**
         * @Required
         * @Word
         * @Size(min=1, max=1400)
         *
         * @var string
         */
        public $stacktrace;

        /**
         * @Word
         * @Size(min=1, max=400)
         *
         * @var string
         */
        public $details;

        public function setDatetime($value)
        {
            $this->datetime = $value;
            $this->checkIntegrity("datetime");
        }

        public function setProject($value)
        {
            $this->project = $value;
            $this->checkIntegrity("project");
        }

        public function setMessage($value)
        {
            $this->message = $value;
            $this->checkIntegrity("message");
        }

        public function setStackTrace($value)
        {
            $this->stacktrace = $value;
            $this->checkIntegrity("stacktrace");
        }

        public function setDetails($value)
        {
            $this->details = $value;
            $this->checkIntegrity("details");
        }

        
    }